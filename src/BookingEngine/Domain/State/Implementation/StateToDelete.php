<?php

namespace BookingEngine\Domain\State\Implementation;

use BookingEngine\Domain\State\AbstractBookingState;

/**
 * Represents an Entity ready to be deleted State
 * Manage State Transition
 * Customer did not pay its Booking, end of failure life cycle
 *
 * @see State Design Pattern
 * @author Guillaume MOREL <github.com/gmorel>
 */
class StateToDelete extends AbstractBookingState
{
    /** Stored in database, easily indexed */
    const KEY = 'to_delete';

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'to_delete';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // @todo I18n with injected Symfony\Component\Translation\TranslatorInterface
        return 'To delete';
    }
}
