// Shape ID generator

function ShapeGenerator()
{

	// Initilization of the bags of values from 0 to 6 (number of tetrominos)
	this.bags = 	[Array.range(0,6),
			 Array.range(0,6),
			 Array.range(0,6),
			 Array.range(0,6)];
					  
	// First bag is bag#0
	this.currentBag = 0;
}
ShapeGenerator.prototype.generate = function() {
	if (this.bags[this.currentBag].length <= 0) { // Is the current bag empty?
		this.replenishBag(); // It is, let's fill it again
	}
	
	// Choose a random index in the range of 0 to the length of the current bag
	var indexToRemove = Math.floor(Math.random() * this.bags[this.currentBag].length);
	
	// The returned value is the value that is located at this index in the current bag
	var returnedValue = this.bags[this.currentBag][indexToRemove];
	
	// This removes the value that was chosen from the current bag
	this.bags[this.currentBag] = this.bags[this.currentBag].remove(returnedValue);
	
	// Increments the bag that will be used for the next shape
	this.incrementCurrentBag();
	
	return returnedValue;
};

ShapeGenerator.prototype.replenishBag = function() {

		// The bag is filled with values from 0 to 6
		this.bags[this.currentBag] = Array.range(0,6);
};

ShapeGenerator.prototype.incrementCurrentBag = function() {
	
	// If the current bag is 3, go back to 0
	if (this.currentBag <= 2) {
		this.currentBag = this.currentBag + 1;
	}
	else { this.currentBag = 0; }
};

ShapeGenerator.prototype.resetBags = function() {
	
	// Resetting all bags to full
	this.bags = 	[Array.range(0,6),
			 Array.range(0,6),
			 Array.range(0,6),
			 Array.range(0,6)];
}