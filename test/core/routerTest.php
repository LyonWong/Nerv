<?php

class routerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataResolve
     */
    public function testResolve($URI, $route)
    {
        $router = router('test');
        $_route = $router->resolve($URI);
        $this->assertSame($route, $_route, "router resolve");
    }

    public function dataResolve()
    {
        return [
            [
                '/',
                ['ctrl' => 'test\control\_', 'args' => []]
            ],
            [
                '/user',
                ['ctrl' => 'test\control\user', 'args' => []]
            ],
            [
                '/user-profile',
                ['ctrl' => 'test\control\user', 'args' => ['profile']]
            ],
            [
                '/api/user-profile',
                ['ctrl' => 'test\control\api\user', 'args' => ['profile']]
            ]
        ];
    }
}