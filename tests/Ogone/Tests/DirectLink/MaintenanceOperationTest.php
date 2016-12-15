<?php
namespace Ogone\Tests\DirectLink;


use Ogone\DirectLink\MaintenanceOperation;
use Ogone\Tests\TestCase;

class MaintenanceOperationTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function OperationCannotBeNull()
    {
        new MaintenanceOperation(null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function OperationMustExist()
    {
        new MaintenanceOperation('NO');
    }

    /** @test */
    public function CanBeRepresentedAsString()
    {
        $this->assertEquals(MaintenanceOperation::OPERATION_AUTHORISATION_RENEW, (string) new MaintenanceOperation(MaintenanceOperation::OPERATION_AUTHORISATION_RENEW));
    }

    /** @test */
    public function itShouldBeComparable()
    {
        $operation = new MaintenanceOperation(MaintenanceOperation::OPERATION_AUTHORISATION_RENEW);
        $this->assertTrue($operation->equals(new MaintenanceOperation(MaintenanceOperation::OPERATION_AUTHORISATION_RENEW)));
        $this->assertFalse($operation->equals(new MaintenanceOperation(MaintenanceOperation::OPERATION_AUTHORISATION_DELETE)));
    }
} 
