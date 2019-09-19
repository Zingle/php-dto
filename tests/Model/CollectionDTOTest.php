<?php

namespace ZingleCom\DTO\Test\Model;

use Doctrine\Common\Collections\ArrayCollection;
use phootwork\collection\Map;
use PHPUnit\Framework\TestCase;
use ZingleCom\DTO\Model\CollectionDTO;
use ZingleCom\DTO\Model\PassThruDTO;

/**
 * Class CollectionDTOTest
 */
class CollectionDTOTest extends TestCase
{
    /**
     * Test map
     */
    public function testMap(): void
    {
        $data = [
            $k = rand() => ['id' => $k],
            $k = rand() => ['id' => $k],
            $k = rand() => ['id' => $k],
            $k = rand() => ['id' => $k],
        ];
        $map = new Map($data);
        $dto = new CollectionDTO($map, PassThruDTO::class);
        /** @var PassThruDTO[] $objects */
        $objects = $dto->getObjects();

        foreach ($objects as $k => $v) {
            $this->assertInstanceOf(PassThruDTO::class, $v);

            $data = $v->toArray();
            $this->assertEquals($k, $data['id']);
        }
    }

    /**
     * Test doctrine collections
     */
    public function testDoctrineCollection(): void
    {
        $arrayCollection = new ArrayCollection([
            ['id' => rand()],
            ['id' => rand()],
            ['id' => rand()],
            ['id' => rand()],
        ]);
        $dto = new CollectionDTO($arrayCollection, PassThruDTO::class);
        /** @var PassThruDTO[] $objects */
        $objects = $dto->getObjects();

        foreach ($objects as $dto) {
            $this->assertInstanceOf(PassThruDTO::class, $dto);
            $data = $dto->toArray();

            $this->assertTrue(is_array($data));
            $keys = array_keys($data);
            $this->assertCount(1, $keys);
        }
    }
}
