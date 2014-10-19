<?hh //strict

namespace Quadtree;

class CollisionDetector implements \Quadtree\ICollisionDetector
{
    public function intersects(Geometry\Bounds $bounds, Insertable $item) : bool
    {
        return $bounds->getBounds()->intersects($item->getBounds());
    }

    public function collide(array<Insertable> $insertedItems, Insertable $item) : bool
    {
        foreach ($insertedItems as $insertedItem) {
            if ($insertedItem->getBounds()->intersects($item->getBounds())) {
                return true;
            }
        }
        return false;
    }
}
