<?php
header ("Content-Type:text/css");
$color = "#746EF1"; // Change your Color Here

function checkhexcolor($color) {
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if( isset( $_GET[ 'color' ] ) AND $_GET[ 'color' ] != '' ) {
    $color = "#".$_GET[ 'color' ];
}

if( !$color OR !checkhexcolor( $color ) ) {
    $color = "#746EF1";
}
 $color ="#fff";
?>

.bg-light,
.table .thead-light th,
.dropdown-item:focus, .dropdown-item:hover,
.jumbotron,
.breadcrumb,
.card,
.page-item.active .page-link 
{
background-color: <?php echo $color ?>!important;
border-radius: 4px;
border: none!important;

}
.jumbotron,
.card,
.breadcrumb{
box-shadow: 0px 2px 11px black;
}
.table thead th{
    text-transform: uppercase;
    border: none;
    white-space: nowrap;
    font-size: 14px;
    font-weight: 700;
}
.table{
        border: none;
        <!-- border-top: 1px solid #ddd; -->
        line-height: 1.4285;
        vertical-align: top;
        cursor: pointer;
}
.table td{
    padding-left: 14px!important;
}
thead{
    border-top:2px solid #eeeeea; 
}
.table tbody tr:hover td, .table tbody tr:hover th {
    background-color: #eeeeea;
}
h4{
    font-size: 1.7em;
    font-weight: 500;
    line-height:1.1;
    color:inherit;
    <!-- text-transform: capitalize; -->
}
.blog-footer{
    <!-- background-color: #e0e8f3 !important; -->
}

.border-dark,
.table .thead-light th,
.page-item.active .page-link
{
border-color: #212529!important;
}

body {
<!-- background-color:  !important; -->
font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
line-height: 1.7;
font-size:14px;
font-weight: 500;
}


