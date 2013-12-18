var canvas = document.getElementsByTagName('canvas')[0]; // Canvas element grabbed
var ctx = canvas.getContext('2d');

var currentX = 0,
    currentY = 0;
var lose = false; // Boolean : true when game is lost
var intervalTick; // Interval : each 250ms
var intervalRender;
var dropping = false; // Check if piece is being dropped, to disable other keys
var currentTetromino = undefined;
var currentRotation = 0;
var ghostY = 0;
var shapeGen = new ShapeGenerator();
var tampon = 1;

var totalCount = 0;

var doubleCount = 0;
var tripleCount = 0;
var tetrisCount = 0;

var canHold = true;

var nextPieces = [];

function drawBlock(x, y) {
	ctx.fillRect(BLOCK_WIDTH * x, BLOCK_HEIGHT * y, BLOCK_WIDTH - 1, BLOCK_HEIGHT - 1);
	ctx.strokeRect(BLOCK_WIDTH * x, BLOCK_HEIGHT * y, BLOCK_WIDTH - 1, BLOCK_HEIGHT - 1);
}

function newShape(first) {
	canHold = true;
	currentRotation = 0; // It's a new shape, its rotation is 0
	if (first) currentTetromino = shapeGen.generate();
	else popNextPiece();

	currentRefresh();
	placeCurrentAtStartingPos()

	calculateGhostY(); // Initial ghost piece calculation
	setTimeout(function () {
		check40Cleared()
	}, 100);
}

function popNextPiece() {
	currentTetromino = nextPieces.shift(); // removes and returns first element
	nextPieces.push(shapeGen.generate()); // adds a piece at end of array				
}

function placeCurrentAtStartingPos() {
	currentX = NEW_SHAPE_OFFSET;
	currentY = 0;
}

function calculateGhostY() {
	ghostY = currentY;
	while (valid(0, 1, current, ghostY)) ghostY++;
}

function render() {
	// Draws wireframe grid for test purposes
	if (!lose) {
	    
		ctx.fillStyle = 'white';
		ctx.strokeStyle = 'black';
		
		ctx.fillStyle = 'green';
		ctx.fillRect(0,0,WIDTH,HEIGHT);
		
		ctx.strokeRect(0,0,WIDTH,HEIGHT); // Border of the board

		for (var x = 0; x < COLS; ++x) {
			for (var y = 0; y < ROWS; ++y) {
				if (board[y][x]) {

					ctx.fillStyle = colors[board[y][x] - 1];
					drawBlock(x, y);
				} else {
					ctx.fillStyle = 'green';
					ctx.strokeStyle = 'green';
					drawBlock(x, y);
				}
			}
		}

		drawTime();
		if (ghostY && !dropping) {
			ctx.strokeStyle = 'white';
			for (var y = 0; y < 4; ++y) {
				for (var x = 0; x < 4; ++x) {
					// GHOST PIECES
					if (current[y][x]) {
						ctx.fillStyle = 'darkgreen';
						drawBlock(currentX + x, ghostY + y); // the x values do not differ from the current blocks' x values, because horizontally, ghost pieces are the same as the current one
					}
				}
			}
		}

		ctx.strokeStyle = 'black';
		for (var y = 0; y < 4; ++y) {
			for (var x = 0; x < 4; ++x) {
				if (current[y][x]) {
					ctx.fillStyle = colors[(current[y][x]) - 1];
					drawBlock(currentX + x, currentY + y);
				}
			}
		}
		drawNextShapesPanel();
		drawHoldPanel();
	}
}

function init() {
	// Initializing 2d arrays for the board and the current tetromino
	for (var y = 0; y < 4; ++y) {
		current[y] = [];
		for (var x = 0; x < 4; ++x) {
			current[y][x] = 0;
		}
	}
	for (var y = 0; y < ROWS; ++y) {
		board[y] = [];
		for (var x = 0; x < COLS; ++x) {
			board[y][x] = 0;
		}
	}

	// Initializing the shape ID generator
	shapeGen.resetBags();
	fillNextPieces();
	heldTetromino = undefined;
}

function fillNextPieces() {
    	shapeGen.resetBags();
	for (var i = 0; i < 5; i++) {
	    nextPieces[i] = shapeGen.generate();
	}
}

function drawTime() {
	ctx.fillStyle = "black";
	ctx.font = '50px "Roboto"';
	ctx.fillText(getChronoString(), 30, 50);
}
function drawNextShapesPanel() {
	var widthN = 80;
	var heightN = 80;
	
	var sizePiece = 15;
	ctx.fillStyle = "black";
	ctx.font = "20px Arial";
	ctx.fillText("NEXT", WIDTH + 20, 20);
	
	for (var i = 0; i < 5; ++i) {
	    ctx.strokeStyle = "blue";
	    ctx.clearRect(WIDTH + 10, 25 + (i * (heightN + 5)), widthN, heightN);
	    ctx.strokeRect(WIDTH + 10, 25 + (i * (heightN + 5)), widthN, heightN);
	    
	    ctx.strokeStyle = "white";
	    ctx.fillStyle = colors[nextPieces[i]];
	    for (var y = 0; y < 4; ++y) {
		for (var x = 0; x < 4; ++x) {
		    var currentNext = TETROMINOS[ nextPieces[i] ][0];
		    //console.warn(currentNext);
		    if (currentNext[x*4+y]) {
			ctx.fillRect(WIDTH + 15 +(y*sizePiece), 35 + (i * (heightN + 5)) + (x*sizePiece), sizePiece, sizePiece);
		    }
		}
	    }
	}
	
	ctx.clearRect(WIDTH + 20, 535, 50, 50);
	ctx.fillText(40 - totalCount, WIDTH + 20, 550);
}

function drawHoldPanel() {
	var widthN = 80;
	var heightN = 80;
	
	var sizePiece = 15;
	var i=5.3;
	
	ctx.fillStyle = "black";
	ctx.font = "20px Arial";
	ctx.fillText("HOLD", WIDTH + 20, 5.9*heightN);
    
	ctx.strokeStyle = "red";
	ctx.clearRect(WIDTH + 10, 25 + (i * (heightN + 5)), widthN, heightN);
	ctx.strokeRect(WIDTH + 10, 25 + (i * (heightN + 5)), widthN, heightN);
	
	ctx.strokeStyle = "white";
	ctx.fillStyle = colors[heldTetromino];
	
	for (var y = 0; y < 4; ++y) {
	    for (var x = 0; x < 4; ++x) {
		if (heldTetromino) {
			var currentNext = TETROMINOS[ heldTetromino ][0];
			//console.warn(currentNext);
			if (currentNext[x*4+y]) {
			    ctx.fillRect(WIDTH + 15 +(y*sizePiece), 35 + (i * (heightN + 5)) + (x*sizePiece), sizePiece, sizePiece);
			}
		}
	    }
	}
}

var heldTetromino = undefined;

function currentRefresh() {
	var shape = TETROMINOS[currentTetromino];
	for (var y = 0; y < 4; ++y) {
		for (var x = 0; x < 4; ++x) {
			var i = 4 * y + x; // 4*y spans the current shape's size, x give the horizontal position of the current block
			if (typeof shape[currentRotation][i] != 'undefined' && shape[currentRotation][i]) {
				current[y][x] = currentTetromino + 1; // 1 is added because the length of the tetromino's array doesn't take index 0 in account
			} else {
				current[y][x] = 0; // 0 means blank block
			}
		}
	}
}

function hold() {
	if (canHold) {
		canHold = false;
		var oldHeld = heldTetromino;
		heldTetromino = currentTetromino;

		if (oldHeld != undefined) {
			currentTetromino = oldHeld;
			placeCurrentAtStartingPos();
		} else {
			newShape();
		}
		currentRefresh();
	}
}

function keyPressed(key) {
	if (!lose) {
		switch (key) {
			case 'hold':
				if (!dropping) {
					hold();
					calculateGhostY();
				}
				break;
			case 'left':
				if (!dropping) {
					if (valid(-1)) {
						currentX--;
						calculateGhostY()
					}
					tampon = 1;
				}
				break;

			case 'right':
				if (!dropping) {
					if (valid(1)) {
						currentX++;
						calculateGhostY()
					}
					tampon = 1;
				}
				break;

			case 'down':
				if (!dropping) {
					if (valid(0, 1)) {
						currentY++;
					}
					clearLines();
				}
				break;

			case 'rotate':
				if (!dropping) {
					var rotated = rotate();
					if (valid(0, 0, rotated)) {
						current = rotated;
						calculateGhostY()
					}
					tampon = 1;
				}
				break;

			case 'drop':
				if (!dropping) {
					dropping = true;
					while (valid(0, 1)) {
						currentY++;
					}
					freeze();
					clearLines();
					newShape();
				}
				break;
			case 'reset':
				newGame();
				break;
		}
	}
}

function rotate() {
	incCurrentRotation();

	var newCurrent = [];
	for (var y = 0; y < 4; ++y) {
		newCurrent[y] = [];
		for (var x = 0; x < 4; ++x) {
			newCurrent[y][x] = TETROMINOS[currentTetromino][currentRotation][x + y * 4];
		}
	}
	return newCurrent;
}

function incCurrentRotation() {
	if (currentRotation < 3) currentRotation++;
	else currentRotation = 0;
}

function clearLines() {
	var lineCounter = 0;
	for (var y = ROWS - 1; y >= 0; --y) { // Starts at BOTTOM (19) ends at TOP (0)
		var lineIsFilled = true; // Assumption that the row is filled
		for (var x = 0; x < COLS; ++x) {
			if (board[y][x] == 0) {
				lineIsFilled = false; // If there's a hole, the line is not filled
				break; // A block in a line is empty, there's no point in continuing the loop because this line has holes
			}
		}

		if (lineIsFilled) { // Row y is a full line
			lineCounter++;
			totalCount++;
			for (var yy = y; yy > 0; --yy) { // Our iterator yy starts at the line that's filled, and ends at line 1 (not 0). Consequently, it's going UP (0 is top)
				for (var x = 0; x < COLS; ++x) {
					board[yy][x] = board[yy - 1][x]; // Every block in this line and all the lines above are replaced by the block that's above it
				}
			}
			++y; // If y isn't incremented and 2 lines or more are cleared at the same time, it will miss every second line.
			// It makes the loop check the line that took the place of the cleared line. This new line has the same y as the cleared one!
		}
	}
	updateCombos(lineCounter);
}

function updateCombos(lineCounter) {
	lineCounter = lineCounter || 0; // Default value is 0 in case there's no parameters passed
	if (lineCounter == 2) {
		doubleCount++;
	} else if (lineCounter == 3) {
		tripleCount++;
	} else if (lineCounter == 4) {
		tetrisCount++;
	}

	refreshLineStats();
}

function check40Cleared() {
	if (totalCount >= 40) {
		sendTime(getChronoString());
		sendStats(tetrisCount, tripleCount, doubleCount, totalCount);
		chronoStop();
		lose = true;
		wellPlayed();
		//setTimeout("refreshScores(tru)", 3000);
	}
}

function sendStats(tetrisCount, tripleCount, doubleCount, lineCounter) {
		j$.post("index.php", {tetrises:tetrisCount, triples:tripleCount, doubles:doubleCount, lines:lineCounter}).done(function(data) {
			console.log(data);
		});
}

function refreshLineStats() {
	j$('span#double')
		.html(doubleCount);
	j$('span#triple')
		.html(tripleCount);
	j$('span#tetris')
		.html(tetrisCount);
	j$('span#total')
		.html(totalCount);
}

function wallkick(xValue) {
	// next up, checking bounds to see if pushing the current shape is needed
	if (xValue < 0) { // a block is out of bounds on the left
		var pushedToRight = -(xValue); // cancels the minus sign, to go right on the x axis
		if (valid(pushedToRight)) {
			console.warn("pushed right")
			currentX += pushedToRight;
			return true;
		}
	}
	if (xValue > 9) { // a block is out of bounds on the right
		var pushedToLeft = 9 - (xValue); // results in negative value , because going left is negative on the x axis
		if (valid(pushedToLeft)) {
			console.warn("pushed left")
			currentX += pushedToLeft;
			return true;
		}
	}
	return false; // this line should never happen, xValue has to be < 0 or > 9 for the current shape to be wallkicked
}

function valid(offsetX, offsetY, newCurrent, ghostY) {
	var isNew = (newCurrent != undefined); // are we using a new current ?
	
	offsetX = offsetX || 0; // if no offset(s) given, use 0
	offsetY = offsetY || 0;
	
	offsetX = currentX + offsetX;
	
	if (ghostY) {
		offsetY = ghostY + offsetY;
	} else {
		offsetY = currentY + offsetY;
	}
	newCurrent = newCurrent || current; // if no newcurrent given, use current

	for (var y = 0; y < 4; ++y) {
		for (var x = 0; x < 4; ++x) {
			if (newCurrent[y][x]) {
				if (typeof board[y + offsetY] == 'undefined' || typeof board[y + offsetY][x + offsetX] == 'undefined' || board[y + offsetY][x + offsetX] // Blocks are adjacent
				|| x + offsetX < 0 || y + offsetY >= ROWS || x + offsetX >= COLS) {

					// SPECIAL CASES

					//WALLKICK

					if (typeof board[y + offsetY] == 'undefined' || typeof board[y + offsetY][x + offsetX] == 'undefined') // Side of the grid
					{
						if (isNew) {
							return wallkick(x + offsetX); // if wallkick is not returned, can't wallkick twice when needed (line piece edge case)
						}
					}


					//GAME OVER
					if (offsetY == 1 && board[y + offsetY][x + offsetX]) {
						setTimeout("gameOver()",100);
					}
					return false;
				}
			}
		}
	}
	return true;
}

function gameOver() {
	lose = true;
	chronoStop();
	gameOverScreen();
}

function wellPlayed() {
	ctx.fillStyle = "rgba(0,0,0,0.8)";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	ctx.fillStyle = "white";
	ctx.strokeStyle = "black";
	ctx.font = "40px Stencil";
	ctx.fillText("WELL PLAYED!", 30, 100);
	ctx.strokeText("WELL PLAYED!", 30, 100);
	ctx.fillText(getChronoString(), 60, HEIGHT / 2 - 120);
	
	
}

function sendTime(timeSent)
{
	j$.post("index.php", {time:timeSent}).done(function(data) {
		refreshScores();
	    });
}

function gameOverScreen() {
	ctx.fillStyle = "rgba(0,0,0,0.8)";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	ctx.fillStyle = "white";
	ctx.strokeStyle = "black";
	ctx.font = "40px Stencil";
	ctx.fillText("GAME OVER!", 30, 100);
	ctx.strokeText("GAME OVER!", 30, 100);
}

function goDown() {

	if (!lose) {
		if (valid(0, 1)) {
			currentY++;
		} else {
			if (tampon == 0) {
				freeze();
				newShape();
				tampon = 1;
			} else tampon--;
			clearLines();
		}
	}
}

function freeze() {
	for (var y = 0; y < 4; ++y) {
		for (var x = 0; x < 4; ++x) {
			if (current[y][x]) {
				board[y + currentY][x + currentX] = current[y][x];
			}
		}
	}
	dropping = false;
}

function newGame() {
	clearInterval(intervalRender);
	intervalRender = setInterval(render, 30);
	ctx.clearRect(0, 0, canvas.width, canvas.height);

	totalCount = 0;
	chronoReset();
	chronoStart();

	lose = false;
	dropping = false;

	doubleCount = 0;
	tripleCount = 0;
	tetrisCount = 0;

	refreshLineStats();

	clearInterval(intervalTick);

	init();
	newShape(true);

	intervalTick = setInterval(goDown, 350);
}

 function loadImages(sources, callback) {
	var images = {};
	var loadedImages = 0;
	var numImages = 0;
	// get num of sources
	for(var src in sources) {
	  numImages++;
	}
	for(var src in sources) {
	  images[src] = new Image();
	  images[src].onload = function() {
		if(++loadedImages >= numImages) {
		  callback(images);
		}
	  };
	  images[src].src = sources[src];
	}
 }
function startScreen() {
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	ctx.fillStyle = "rgba(0,0,0,0.8)";
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	
	var sources = {
		ps : './game/engine/ps.jpg',
		bg : './game/engine/bg.jpg'
	}
	loadImages(sources, function(images) {
		ctx.drawImage(images.bg, 0,0);
		ctx.fillStyle = "rgba(0,0,0,0.5)";
		ctx.fillRect(0, 0, canvas.width, canvas.height);
		ctx.drawImage(images.ps, (canvas.width-images.ps.width)/2,(canvas.height-images.ps.height)/2);
	});
}

// CODE EXECUTE
//init();
//newGame();
startScreen();
