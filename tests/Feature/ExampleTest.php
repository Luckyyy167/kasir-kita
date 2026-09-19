<?php

test('the application redirects root to pos', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('pos.index'));
});
