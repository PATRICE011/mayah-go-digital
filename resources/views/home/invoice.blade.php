@extends('home.layout')
@section('title','Mayah Store - Invoice')

<div class = "invoice-wrapper" id = "print-area">
    <div class = "invoice">
        <div class = "invoice-container">
            <div class = "invoice-head">
                <div class = "invoice-head-top">
                    <div class = "invoice-head-top-left text-start">
                        <img src = "{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}">
                    </div>

                    <div class = "invoice-head-top-right text-end">
                        <h3>Invoice</h3>
                    </div>
                </div>

                <div class = "hr"></div>
                <div class = "invoice-head-middle">
                    <div class = "invoice-head-middle-left text-start">
                        <p><span class = "text-bold">Date</span>: 05/12/2020</p>
                    </div>

                    <div class = "invoice-head-middle-right text-end">
                        <p><spanf class = "text-bold">Invoice No:</span>16789</p>
                    </div>
                </div>

                <div class = "hr"></div>
                <div class = "invoice-head-bottom">
                    <div class = "invoice-head-bottom-left">
                        <ul>
                            <li class = 'text-bold'>Invoiced To:</li>
                            <li>Smith Rhodes</li>
                            <li>15 Hodges Mews, High Wycombe</li>
                            <li>HP12 3JL</li>
                            <li>United Kingdom</li>
                        </ul>
                    </div>

                    <div class = "invoice-head-bottom-right">
                        <ul class = "text-end">
                            <li class = 'text-bold'>Pay To:</li>
                            <li>Koice Inc.</li>
                            <li>2705 N. Enterprise</li>
                            <li>Orange, CA 89438</li>
                            <li>contact@koiceinc.com</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class = "overflow-view">
                <div class = "invoice-body">
                    <table>
                        <thead>
                            <tr>
                                <td class = "text-bold">Product Name</td>
                                <td class = "text-bold">Description</td>
                                <td class = "text-bold">Price</td>
                                <td class = "text-bold">Quantity</td>
                                <td class = "text-bold">Amount</td>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Design</td>
                                <td>Creating a website design</td>
                                <td>$50.00</td>
                                <td>10</td>
                                <td class = "text-end">$500.00</td>
                            </tr>

                            <tr>
                                <td>Development</td>
                                <td>Website Development</td>
                                <td>$50.00</td>
                                <td>10</td>
                                <td class = "text-end">$500.00</td>
                            </tr>

                            <tr>
                                <td>SEO</td>
                                <td>Optimize the site for search engines (SEO)</td>
                                <td>$50.00</td>
                                <td>10</td>
                                <td class = "text-end">$500.00</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class = "invoice-body-bottom">
                        <div class = "invoice-body-info-item">
                            <div class = "info-item-td text-end text-bold">Total:</div>
                            <div class = "info-item-td text-end">$21365.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class = "invoice-foot text-center">
                <p>
                    <span class = "text-bold text-center">NOTE:&nbsp;</span>This is computer generated receipt and does not require physical signature.
                </p>
            </div>
        </div>
    </div>
</div>