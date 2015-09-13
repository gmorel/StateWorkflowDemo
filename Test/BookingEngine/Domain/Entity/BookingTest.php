<?php

namespace Gmorel\StateWorkflowBundle\Test\BookingEngine\Domain\Entity;

use BookingEngine\Domain\Entity\Booking;
use BookingEngine\Domain\State\Implementation\StateCancelled;
use BookingEngine\Domain\State\Implementation\StateIncomplete;
use BookingEngine\Domain\State\Implementation\StatePaid;
use BookingEngine\Domain\State\Implementation\StateToDelete;
use BookingEngine\Domain\State\Implementation\StateWaitingPayment;
use Gmorel\StateWorkflowBundle\StateEngine\StateWorkflow;

/**
 * @author Guillaume MOREL <github.com/gmorel>
 */
class BookingTest extends \PHPUnit_Framework_TestCase
{
    const CALL_ORIGINAL_METHODS = true;

    public function test_states_available_workflow()
    {
        // Given
        $stateCancelled = new StateCancelled();
        $stateIncompleteStub = $this->mockStateIncomplete();
        $statePaid = new StatePaid();
        $stateToDelete = new StateToDelete();
        $stateWaitingForPayment = new StateWaitingPayment();

        $stateWorkflow = new StateWorkflow('Booking Workflow', 'service_id');
        $stateWorkflow->addAvailableState($stateCancelled);
        $stateWorkflow->addAvailableState($stateIncompleteStub);
        $stateWorkflow->addAvailableState($statePaid);
        $stateWorkflow->addAvailableState($stateToDelete);
        $stateWorkflow->addAvailableState($stateWaitingForPayment);

        // Initialize entity state to incomplete
        $stateWorkflow->setStateAsDefault($stateIncompleteStub->getKey());
        $booking = new Booking($stateWorkflow, 42.00);

        /** @var StateIncomplete $currentState */
        $currentState = $booking->getState($stateWorkflow);
        $this->assertEquals($stateIncompleteStub->getKey(), $currentState->getKey());

        // When cancel booking
        $this->expectCancelMethodFromIncompleteStateToBeCalled($stateIncompleteStub);

        $booking->cancel($stateWorkflow);

        // Then
        /** @var StateCancelled $currentState */
        $currentState = $booking->getState($stateWorkflow);
        $this->assertEquals($stateCancelled->getKey(), $currentState->getKey());
    }

    /**
     * @return \BookingEngine\Domain\State\Implementation\StateIncomplete|\PHPUnit_Framework_MockObject_MockObject
     */
    private function mockStateIncomplete()
    {
        return $this->getMock(
            'BookingEngine\Domain\State\Implementation\StateIncomplete',
            array(),
            array(),
            '',
            true,
            true,
            true,
            false,
            self::CALL_ORIGINAL_METHODS
        );
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $stateIncompleteStub
     */
    private function expectCancelMethodFromIncompleteStateToBeCalled($stateIncompleteStub)
    {
        $stateIncompleteStub->expects($this->once())
            ->method('cancelBooking')
            ->withAnyParameters();
    }
}
