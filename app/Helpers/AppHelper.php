<?php
use Illuminate\Support\Number;


function format_money($amount){
    return Number::currency($amount ?? 0);
}