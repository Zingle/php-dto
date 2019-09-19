<?php

namespace ZingleCom\DTO\Test;

use PHPUnit\Framework\TestCase;
use ZingleCom\DTO\Model\CollectionDTO;
use ZingleCom\DTO\Model\PassThruDTO;
use ZingleCom\DTO\Resolver\Resolver;

/**
 * Class ResolverTest
 */
class ResolverTest extends TestCase
{
    /**
     * Test ArrayList resolves
     */
    public function testArrayList()
    {
        $data = [['value' => rand()], ['value' => rand()], ['value' => rand()], ['value' => rand()]];
        $list = new CollectionDTO($data, PassThruDTO::class);
        $resolver     = new Resolver();
        $resolvedData = $resolver->resolve($list);

        $this->assertCount(4, $resolvedData);
        foreach ($data as $i => $v) {
            $this->assertEquals($v['value'], $resolvedData[$i]['value']);
        }
    }

    /**
     * Test embedded DTOs
     */
    public function testEmbeddedDTOs()
    {
        $account = ['id' => rand()];
        $data = [
            'name'    => 'Tom',
            'account' => new PassThruDTO($account),
        ];
        $resolver     = new Resolver();
        $resolvedData = $resolver->resolve(new PassThruDTO($data));
        $this->assertCount(2, $resolvedData);
        $this->assertEquals($data['name'], $resolvedData['name']);
        $this->assertEquals($account['id'], $resolvedData['account']['id']);
    }
}
