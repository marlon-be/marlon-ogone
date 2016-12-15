<?php
namespace Ogone\Tests\DirectLink;


use Ogone\DirectLink\PaymentOperation;
use Ogone\Tests\TestCase;

class PaymentOperationTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function OperationCannotBeNull()
    {
        new PaymentOperation(null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function OperationMustExist()
    {
        new PaymentOperation('NO');
    }

    /** @test */
    public function CanBeRepresentedAsString()
    {
        $this->assertEquals(PaymentOperation::REQUEST_FOR_AUTHORISATION, (string) new PaymentOperation(PaymentOperation::REQUEST_FOR_AUTHORISATION));
    }

    /** @test */
    public function itShouldBeComparable()
    {
        $operation = new PaymentOperation(PaymentOperation::REQUEST_FOR_AUTHORISATION);
        $this->assertTrue($operation->equals(new PaymentOperation(PaymentOperation::REQUEST_FOR_AUTHORISATION)));
        $this->assertFalse($operation->equals(new PaymentOperation(PaymentOperation::REQUEST_FOR_DIRECT_SALE)));
    }
} 
