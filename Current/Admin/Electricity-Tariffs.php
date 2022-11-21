<?php

require_once 'Admin-Header.php';

?>

<div class="row justify-content-center wrapper">
    <div class="col-lg-12 p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">
            <h2 class="align-items-center text-center">Customer Gas Tariffs</h2>
            <div class="col-md-4 mb-3">
                <div class="card p-2">
                    <div class="card-body">
                        <h2>Domestic&nbsp;<i class="fa fa-home" aria-hidden="true"></i></h2>
                        <table class="table table-striped table-hover" style="text-align: center;">
                            <thead style="font-weight: bold;">
                                <tr>
                                    <th> Consumption</th>
                                    <th>Charge<br>(mmbtu)</th>
                                    <th>Fixed Charge<br>(mmbtu)</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>0-30</td>
                                <td>3.00</td>
                                <td>30.00</td>
                            </tr>
                            <tr>
                                <td>31-60</td>
                                <td>4.70</td>
                                <td>60.00</td>
                            </tr>
                            <tr>
                                <td>61-90</td>
                                <td>7.50</td>
                                <td>90.00</td>
                            </tr>
                            <tr>
                                <td>91-120</td>
                                <td>21.00</td>
                                <td>315.00</td>
                            </tr>
                            <tr>
                                <td>121-180</td>
                                <td>24.00</td>
                                <td>315.00</td>
                            </tr>
                            <tr>
                                <td>180 ></td>
                                <td>36.00</td>
                                <td>315.00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-3 p-2">
                    <div class="card-body">
                        <h2>Industry&nbsp;<i class="fa fa-industry" aria-hidden="true"></i></h2>
                        <table class="table table-striped table-hover" style="text-align: center;">
                            <thead style="font-weight: bold;">
                                <tr>
                                    <th>Consumption</th>
                                    <th>Charge<br>(mmbtu)</th>
                                    <th>Fixed Charge<br>(mmbtu)</th>
                                    <th>Max Demand Charge<br>(Rs/kVA)</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>1-1</td>
                                <td>10.50</td>
                                <td>240.00</td>
                                <td>-</td>
                            </tr>
                            <td colspan="4" style="padding-right: 350px;">1-2</td>

                            <tr>
                                <td>Day</td>
                                <td>10.45</td>
                                <td rowspan="3" style="padding-top: 50px;">3000.00</td>
                                <td rowspan="3" style="padding-top: 50px;">850.00</td>
                            </tr>
                            <tr>
                                <td>Peak</td>
                                <td>13.60</td>
                            </tr>
                            <tr>
                                <td>Off Peak</td>
                                <td>7.35</td>
                            </tr>
                            <td colspan="4" style="padding-right: 350px;">1-3</td>
                            <tr>
                                <td>Day</td>
                                <td>10.25</td>
                                <td rowspan="3" style="padding-top: 50px;">3000.00</td>
                                <td rowspan="3" style="padding-top: 50px;">750.00</td>
                            </tr>
                            <tr>
                                <td>Peak</td>
                                <td>13.40</td>
                            </tr>
                            <tr>
                                <td>Off Peak</td>
                                <td>7.15</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            

<?php

require_once 'Admin-Footer.php';

?>