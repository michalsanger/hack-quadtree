<?hh //strict

namespace Quadtree;

interface ICollisionDetector
{
    /**
     * Test if insertable item intersects with bounds
     */
    public function intersects(Geometry\Bounds $bounds, Insertable $item) : bool;

    /**
     * Test if new item collides with collection of items
     */
    public function collide(array<Insertable> $insertedItems, Insertable $item) : bool;
}
