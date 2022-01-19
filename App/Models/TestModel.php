<?php

namespace Albet\Ppob\Models;

class TestModel extends BaseModel
{
    // public function test()
    // {
    //     return $this->db->table('testing')->field('tipe', 'harga')->value('hehe', 5000)->where('id', 1)->update();
    // }
    public function test()
    {
        return $this->db->table('testing')->orderBy(['id'], 'ASC')->get();
    }
}
