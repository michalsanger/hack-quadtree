<?hh //strict

namespace Quadtree\Geometry;

/**
 * A Bounds represents a rectangle on a two-dimensional plane
 */
class Bounds implements \Quadtree\Insertable
{   
	public function __construct(
		private float $width, 
		private float $height, 
		private float $left = 0.0,
		private float $top = 0.0
	){}
	
	public function getWidth() : float
	{
		return $this->width;
	}

	public function getHeight() : float
	{
		return $this->height;
	}

	public function getLeft() : float
	{
		return $this->left;
	}

	public function getTop() : float
	{
		return $this->top;
	}
	
	/**
	 * Returns true if the given point is in this bounds
	 */
	public function containsPoint(Point $point) : bool
	{
		$leftIn = $point->getLeft() >= $this->left && $point->getLeft() < ($this->left + $this->width);
		$topIn = $point->getTop() >= $this->top && $point->getTop() < ($this->top + $this->height);
		return $leftIn && $topIn;
	}
	
	/**
	 * Computes the center of this bounds
	 */
	public function getCenter() : Point
	{
		$left = $this->left + ($this->width / 2);
		$top = $this->top + ($this->height / 2);
		return new Point($left, $top);
	}
	
	/**
	 * Returns true if this bounds shares any points with other bounds
	 */
	public function intersects(Bounds $other) : bool
	{
		return $this->left <= $other->getLeft() + $other->getWidth()
			&& $other->getLeft() <= $this->left + $this->width
			&& $this->top <= $other->getTop() + $other->getHeight()
			&& $other->getTop() <= $this->top + $this->height;
	}
	
	/**
	 * Returns the intersection of two bounds
	 */
	public function intersection(Bounds $other) : ?Bounds
	{
		$x0 = max($this->left, $other->getLeft());
		$x1 = min($this->left + $this->width, $other->getLeft() + $other->getWidth());
		if ($x0 <= $x1) {
			$y0 = max($this->top, $other->getTop());
			$y1 = min($this->top + $this->height, $other->getTop() + $other->getHeight());
			if ($y0 <= $y1) {
				return new self($x1 - $x0, $y1 - $y0, $x0, $y0);
			}
		}
		return null;
	}

	/**
	 * Get 2D envelope
	 */
	public function getBounds() : Bounds
	{
		return $this;
	}
	
	/**
	 * Calculate size of area
	 */
	public function getArea() : float
	{
		return $this->width * $this->height;
	}
	
	/**
	 * Comparison function
	 */
	public function equals(Bounds $other) : bool
	{
		return $this->width === $other->getWidth()
			&& $this->height === $other->getHeight()
			&& $this->left === $other->getLeft()
			&& $this->top === $other->getTop();
	}
}