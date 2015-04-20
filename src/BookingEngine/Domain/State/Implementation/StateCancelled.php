<?php

namespace BookingEngine\Domain\State\Implementation;

use BookingEngine\Domain\State\AbstractBookingState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;

/**
 * Represents a cancelled Entity State
 * Manage State Transition
 * Booking was cancelled by Customer
 *
 * @see State Design Pattern
 * @author Guillaume MOREL <github.com/gmorel>
 */
class StateCancelled extends AbstractBookingState
{
    /** Stored in database, easily indexed */
    const KEY = 'cancelled';

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
        return 'Cancelled';
    }

    /**
     * {@inheritdoc}
     */
    public function setBookingToBeDeleted(HasStateInterface $booking)
    {
        $newState = $this->getStateFromStateId(StateToDelete::KEY, __METHOD__, $booking);
        if ($newState) {
            $booking->changeState($this->getStateWorkflow(), $newState);

            // Implement necessary relevant transition here
        }

        return $newState;
    }
}
