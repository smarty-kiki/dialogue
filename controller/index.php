<?php

if_get('/', function ()
{
    return 'hello world';
});

if_post('/test', function ()
{
    return 'hello world';
});
