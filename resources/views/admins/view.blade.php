@extends('admins.layout')
@section('title')
@section('content')
<main class="container section">
    <h2>View</h2>
    <div class="order-container">
        <div class="order-header">
            <div class="order-details">
                <h1>Order ID: #2908243</h1>
                <div class="status">
                    <span class="badge unpaid">
                        Unpaid
                    </span>

                    <span class="badge pending">
                        Pending
                    </span>
                </div>
            </div>

            <div class="order-actions">
                <button class="reject-btn">
                    <i class="fa fa-times"></i>Reject
                </button>

                <button class="accept-btn">
                    <i class="fa fa-check"></i>Accept
                </button>
            </div>
        </div>

        <div class="order-details">
            <div class="order-timing">
                <p>
                    <i class="fa fa-clock-o"></i> 12:47 AM, 30-08-2024
                </p>
                
                <p>Payment Type: Cash On Delivery</p>
                <p>Order Type: Delivery</p>
            </div>
        </div>
    </div>

    <div class="SECOND">
        <div class="order-details-container">
            <h2>Order Details</h2>
            <ul class="order-items-list">
                <li class="order-item">
                    <div class="item-quantity">
                        <span>1</span>
                    </div>
                    <img src="hoodie.jpg" alt="Air Hoodie">
                    <div class="item-info">
                        <p class="item-name">Air Hoodie</p>
                        <p class="item-attributes">Black | S</p>
                        <p class="item-price">$100.00</p>
                    </div>
                </li>

                <li class="order-item">
                    <div class="item-quantity">
                        <span>1</span>
                    </div>
                    <img src="shoes.jpg" alt="Ultra Bounce Shoes">
                    <div class="item-info">
                        <p class="item-name">Ultra Bounce Shoes</p>
                        <p class="item-attributes">Black | S</p>
                        <p class="item-price">$80.00</p>
                    </div>
                </li>

                <li class="order-item">
                    <div class="item-quantity">
                        <span>1</span>
                    </div>
                    <img src="hat.jpg" alt="Essential Hat">
                    <div class="item-info">
                        <p class="item-name">Essential Hat</p>
                        <p class="item-attributes">Black | S</p>
                        <p class="item-price">$60.00</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="idk">
            <div class="order-summary-container">
                <table class="summary-table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="order-price">$240.00</td>
                    </tr>

                    <tr>
                        <td>Discount</td>
                        <td class="order-price">$0.00</td>
                    </tr>

                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td class="order-price"><strong>$278.00</strong></td>
                    </tr>
                </table>
            </div>

            <div class="billing-address-container">
                <h2>Billing Address</h2>
                <div class="billing-details">
                    <div class="avatar">
                        <img src="avatar.png" alt="Avatar">
                        <p>Will Smith</p>
                    </div>
                    
                    <div class="contact-info">
                        <p><i class="fa fa-envelope"></i> customer@example.com</p>
                        <p><i class="fa fa-phone"></i> +880125333344</p>
                        <p><i class="fa fa-home"></i> House 3, Road 1, Block C, Mirpur 2, Dhaka, Bangladesh, 1216</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</main>
@endsection

