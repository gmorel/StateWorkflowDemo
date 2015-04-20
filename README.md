<img src ="https://raw.githubusercontent.com/spec-gen/state-workflow-spec-gen-bundle/master/doc/symfony.png" alt="Symfony 2" align="right"/>
State Workflow Demo
===================

Helping you implementing a complex yet easily maintainable workflow.
---------------------------------------------

Keywords : State Design Pattern, Workflow, Finite State Machine, Symfony2


A `demonstration` project for [StateWorkflowBundle](https://github.com/gmorel/StateWorkflowBundle).

![Demo Booking Workflow simple](https://raw.githubusercontent.com/spec-gen/state-workflow-spec-gen-bundle/master/doc/demo-booking-workflow.png "Demo Booking Workflow simple")

### Context

A [Booking Entity](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/Entity/Booking.php) 
being first `incomplete`, then `waiting for payment`, then `paid` then `to delete` or `cancelled`.
- **State** : [BookingStateInterface](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/BookingStateInterface.php) instance
- **Transition** : [BookingStateInterface](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/BookingStateInterface.php) method

### State declaration
The following States are all implementing [BookingStateInterface](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/BookingStateInterface.php)
- [StateIncomplete](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/Implementation/StateIncomplete.php)
- [StateCancelled](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/Implementation/StateCancelled.php)
- [StateWaitingPayment](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/Implementation/StateWaitingPayment.php)
- [StatePaid](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/Implementation/StatePaid.php)
- [StateToDelete](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/Implementation/StateToDelete.php)

### Default transitions - disabled
All available transitions are defined in [BookingStateInterface](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/BookingStateInterface.php) 
```php
interface BookingStateInterface extends StateInterface
{
    public function setBookingAsWaitingForPayment(HasStateInterface $booking);
    public function setBookingAsPaid(HasStateInterface $booking);
    public function cancelBooking(HasStateInterface $booking);
    public function setBookingToBeDeleted(HasStateInterface $booking);
}
```

All `States` are implementing [AbstractBookingState](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/AbstractBookingState.php).
Hence all `transitions` are disabled by default because of 
```php
throw $this->buildUnsupportedTransitionException(__METHOD__, $booking);
```

### Enabled transitions
Transitions are enabled when a [BookingStateInterface](https://github.com/gmorel/StateWorkflowDemo/blob/master/src/BookingEngine/Domain/State/BookingStateInterface.php) 
`transition` method is overridden.
```php
public function setBookingAsPaid(HasStateInterface $booking)
{
    $newState = $this->getStateFromStateId(StatePaid::KEY, __METHOD__, $booking);
    if ($newState) {
        $booking->changeState($this->getStateWorkflow(), $newState);

        // Implement necessary relevant transition here
    }

    return $newState;
}
```

Inside these `transition` methods you can do what ever your want. And since each State is a service. 
You can **inject** whatever you want.
- Log
- Event Sourcing
- Assertion
- Send mail
- etc..


### Examples
Here is the generated Workflow Specification generated using the [SpecGen](https://github.com/spec-gen/state-workflow-spec-gen-bundle) command CLI `sf spec-gen:state-workflow:generate-specifications` : 
- Simple workflow [demo.booking_engine.state_workflow.html](http://rawgit.com/gmorel/StateWorkflowDemo/master/specification/workflow/demo.booking_engine.state_workflow.html)
- More complex workflow [demo.quote_engine.state_workflow.html](http://rawgit.com/gmorel/StateWorkflowDemo/master/specification/workflow/demo.quote_engine.state_workflow.html)

[Cytoscape](http://www.cytoscape.org) generates the workflow layout randomly. 
If the layout doesn't suit well, refresh. 
Don't hesitate to drag and drop.
