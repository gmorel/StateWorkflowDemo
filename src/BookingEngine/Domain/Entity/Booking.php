<?php

namespace BookingEngine\Domain\Entity;

use BookingEngine\Domain\State\BookingStateInterface;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;
use Gmorel\StateWorkflowBundle\StateEngine\StateWorkflow;
use Gmorel\StateWorkflowBundle\StateEngine\StateInterface;
use BookingEngine\Infra\DoctrineEntity\Booking as BookingDoctrineEntity;

/**
 * Entity example
 * @author Guillaume MOREL <github.com/gmorel>
 */
class Booking extends BookingDoctrineEntity implements HasStateInterface
{
    public function __construct(StateWorkflow $stateWorkflow, $priceTotal)
    {
        $stateWorkflow->getDefaultState()->initialize($this);

        $this->setPriceTotal($priceTotal);
    }


    /**
     * {@inheritdoc}
     */
    public function setPriceTotal($priceTotal)
    {
        if ($priceTotal < 0) {
            throw new \DomainException(
                sprintf(
                    'Booking total price must be positive. Received %s.',
                    $priceTotal
                )
            );
        }
        $this->priceTotal = floatval($priceTotal);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changeState(StateWorkflow $stateWorkflow, StateInterface $newState)
    {
        $stateWorkflow->guardExistingState($newState->getKey());
        $this->setStateKey($newState->getKey());

        return $this;
    }

    /**
     * {@inheritdoc}
     * @return BookingStateInterface
     */
    public function getState(StateWorkflow $stateWorkflow)
    {
        return $stateWorkflow->getStateFromKey($this->stateKey);
    }

    /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $stateWorkflow
     *
     * @return \BookingEngine\Domain\State\Implementation\StateWaitingPayment New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    public function setAsWaitingForPayment(StateWorkflow $stateWorkflow)
    {
        return $this->getState($stateWorkflow)->setBookingAsWaitingForPayment($this);
    }

    /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $stateWorkflow
     *
     * @return \BookingEngine\Domain\State\Implementation\StatePaid New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    public function setAsPaid(StateWorkflow $stateWorkflow)
    {
        return $this->getState($stateWorkflow)->setBookingAsPaid($this);
    }

    /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $stateWorkflow
     *
     * @return \BookingEngine\Domain\State\Implementation\StateCancelled
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    public function cancel(StateWorkflow $stateWorkflow)
    {
        return $this->getState($stateWorkflow)->cancelBooking($this);
    }


    /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $stateWorkflow
     *
     * @return \BookingEngine\Domain\State\Implementation\StateToDelete New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    public function setToBeDeleted(StateWorkflow $stateWorkflow)
    {
        return $this->getState($stateWorkflow)->setBookingToBeDeleted($this);
    }
}
