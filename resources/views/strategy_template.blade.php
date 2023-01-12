class Strategy extends StrategyAlgorithm
{
    /**
     * Initialize the strategy
     */
    initialize()
    {

    }

    /**
     * OnTick (individual price movement)
     * - when a symbol's price is moved
     */
    onTick(candlestick)
    {
    }

    /**
     * OnOrderEvent (individual order events)
     * - when a order has an update
     */
    onOrderEvent(orderEvent)
    {
        // STATUSES
        // New - pre-submission order
        // Submitted - Order submitted to the market
        // PartiallyFilled - Partially filled, in market order
        // Filled - Completed, filled, in market order
        // UpdatePending - Order waiting for confirmation of update
        // Updated - Order updated before it was filled
        // CancelPending - Order waiting for confirmation of cancellation
        // Canceled - Order cancelled before it was filled
        // None - No order status yet (?)

        // example of cancelling an order
        // if (orderEvent.orderId == this.marketOrderTicket.orderId)
        // {
        //     this.marketOrderTicket.Cancel()
        // }

        // example of updating an order
        // if (orderEvent.orderId == this.marketOrderTicket.orderId)
        // {
        //     const orderUpdate = new OrderUpdate();
        //     orderUpdate.limitPrice = 10
        //     orderUpdate.stopPrice = 9
        //     this.marketOrderTicket.Update(orderUpdate)
        // }
    }
}