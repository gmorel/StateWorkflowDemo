<?php

namespace BookingEngine\Domain\State\Implementation;

use BookingEngine\Domain\State\AbstractBookingState;

/**
 * Represents a paid Entity State
 * Manage State Transition
 * Customer paid its Booking, end of success life cycle
 *
 * @see State Design Pattern
 * @author Guillaume MOREL <github.com/gmorel>
 */
class StatePaid extends AbstractBookingState
{
    /** Stored in database, easily indexed */
    const KEY = 'paid';

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'paid';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // @todo I18n with injected Symfony\Component\Translation\TranslatorInterface
        return 'Paid';
    }
}
