<?hh //strict

namespace Quadtree;

interface Insertable
{
    /**
     * Get 2D envelope
     */
    public function getBounds() : Geometry\Bounds;
}
