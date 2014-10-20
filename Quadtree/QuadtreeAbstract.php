<?hh //strict

namespace Quadtree;

use Quadtree\Geometry\Bounds;

abstract class QuadtreeAbstract
{
    const CAPACITY = 4;

    private int $capacity;

    private array<Insertable> $items = [];

    private QuadtreeAbstract $nw;

    private QuadtreeAbstract $ne;

    private QuadtreeAbstract $sw;

    private QuadtreeAbstract $se;

    public function __construct(
        private ICollisionDetector $detector,
        private Geometry\Bounds $bounds,
        ?int $leafCapacity
    )
    {
        $this->capacity = is_null($leafCapacity) ? static::CAPACITY : $leafCapacity;
    }

    public function insert(Insertable $item) : bool
    {
        if (!$this->detector->intersects($this->bounds, $item)) {
            return false;
        }
        if ($this->detector->collide($this->items, $item)) {
            return false;
        }

        if ($this->nw === null && count($this->items) < $this->capacity) {
            $this->items[] = $item;
            return true;
        } else {
            if (count($this->items) >= $this->capacity) {
                $this->subdivide();
            }
            $nwIn = $this->nw->insert($item);
            $neIn = $this->ne->insert($item);
            $swIn = $this->sw->insert($item);
            $seIn = $this->se->insert($item);
            return $nwIn || $neIn || $swIn || $seIn;
        }
    }

    /**
     * Number of levels in the tree
     */
    public function getDepth() : int
    {
        if ($this->nw === null) {
            return 0;
        } else {
            $max = max($this->nw->getDepth(), $this->ne->getDepth(), $this->sw->getDepth(), $this->se->getDepth());
            return 1 + $max;
        }
    }

    /**
     * Number of items in the tree
     */
    public function getSize() : int
    {
        if ($this->nw === null) {
            return count($this->items);
        } else {
            return $this->nw->getSize() + $this->ne->getSize() + $this->sw->getSize() + $this->se->getSize();
        }
    }

    private function subdivide() : void
    {
        list($this->nw, $this->ne, $this->sw, $this->se) = $this->getDividedBounds();
        foreach ($this->items as $item) {
            $this->nw->insert($item);
            $this->ne->insert($item);
            $this->sw->insert($item);
            $this->se->insert($item);
        }
        $this->items = array();
    }

    private function getDividedBounds() : array<QuadtreeAbstract>
    {
        $c = $this->bounds->getCenter();
        $width = $this->bounds->getWidth() / 2;
        $height = $this->bounds->getHeight() / 2;

        // $nw = new static(new Bounds($width, $height, $c->getLeft() - $width, $c->getTop() - $height), $this->capacity);
        // $ne = new static(new Bounds($width, $height, $c->getLeft(), $c->getTop() - $height), $this->capacity);
        // $sw = new static(new Bounds($width, $height, $c->getLeft() - $width, $c->getTop()), $this->capacity);
        // $se = new static(new Bounds($width, $height, $c->getLeft(), $c->getTop()), $this->capacity);
        // return array($nw, $ne, $sw, $se);
        return [];
    }
}