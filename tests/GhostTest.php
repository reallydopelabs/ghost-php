<?php

use ReallyDope\Ghost\Admin;
use ReallyDope\Ghost\Ghost;

test('it can create an admin client', function () {
    $admin = Ghost::admin('ghost.org', apiKey());

    expect($admin)
        ->toBeInstanceOf(Admin::class);
});
