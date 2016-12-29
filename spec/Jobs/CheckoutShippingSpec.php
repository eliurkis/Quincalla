<?php

namespace spec\Quincalla\Jobs;

use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;

class CheckoutShippingSpec extends ObjectBehavior
{
    public function let(Request $request)
    {
        $this->beConstructedWith($request);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Quincalla\Jobs\CheckoutShipping');
    }
}
