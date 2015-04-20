<?php

namespace BookingEngine\Domain\State\Implementation;

use BookingEngine\Domain\State\AbstractBookingState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;

/**
 * Represents an incomplete Entity State
 * Manage State Transition
 * Only minimum information are stored yet
 *
 * @see State Design Pattern
 * @author Guillaume MOREL <github.com/gmorel>
 */
class StateIncomplete extends AbstractBookingState
{
    /** Stored in database, easily indexed */
    const KEY = 'incomplete';

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return self::KEY;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // @todo I18n with injected Symfony\Component\Translation\TranslatorInterface
        return 'Incomplete';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(HasStateInterface $booking)
    {
        $booking->changeState($this->getStateWorkflow(), $this);

        // Implement necessary relevant transition here

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBookingAsWaitingForPayment(HasStateInterface $booking)
    {
        $newState = $this->getStateFromStateId(StateWaitingPayment::KEY, __METHOD__, $booking);
        if ($newState) {
            $booking->changeState($this->getStateWorkflow(), $newState);

            // Implement necessary relevant transition here
        }

        return $newState;
    }

    /**
     * {@inheritdoc}
     */
    public function cancelBooking(HasStateInterface $booking)
    {
        $newState = $this->getStateFromStateId(StateCancelled::KEY, __METHOD__, $booking);
        if ($newState) {
            $booking->changeState($this->getStateWorkflow(), $newState);

            // Implement necessary relevant transition here
        }

        return $newState;
    }
}
