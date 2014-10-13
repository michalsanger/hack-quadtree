<?hh //strict

namespace Quadtree\Geometry;

/**
 * A point on a two-dimensional plane
 */
class Point
{
	private ?Bounds $bounds;
	
	public function __construct(private float $left, private float $top) {}
	
	public function getLeft() : float
	{
		return $this->left;
	}

	public function getTop() : float
	{
		return $this->top;
	}
	
	/**
	 * Comparison function
	 */
	public function equals(Point $point) : bool
	{
		return $this->left === $point->getLeft() && $this->top === $point->getTop();
	}
	
	/**
	 * Get 2D envelope
	 */
	public function getBounds() : Bounds
	{
		if ($this->bounds === null) {
			$this->bounds = new Bounds(0.0, 0.0, $this->left, $this->top);
		}
		return $this->bounds;
	}

}