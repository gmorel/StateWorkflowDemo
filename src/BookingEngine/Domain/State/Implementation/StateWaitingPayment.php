<?php

namespace BookingEngine\Domain\State\Implementation;

use BookingEngine\Domain\State\AbstractBookingState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;

/**
 * Represents an Entity ready to be paid State
 * Manage State Transition
 * All data are stored but Booking is not paid yet
 *
 * @see State Design Pattern
 * @author Guillaume MOREL <github.com/gmorel>
 */
class StateWaitingPayment extends AbstractBookingState
{
    /** Stored in database, easily indexed */
    const KEY = 'waiting_for_payment';

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
        return 'Waiting for payment';
    }

    /**
     * {@inheritdoc}
     */
    public function setBookingAsPaid(HasStateInterface $booking)
    {
        $newState = $this->getStateFromStateId(StatePaid::KEY, __METHOD__, $booking);
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
