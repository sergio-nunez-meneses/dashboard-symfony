const SHOW_CELLS = document.getElementById('showCells'),
    HIDDEN_CELLS = document.querySelectorAll('.hiddenCell');

function display() {
    for (let i = 0; i < HIDDEN_CELLS.length; i++) {
        if (HIDDEN_CELLS[i].classList.contains('d-none')) {
            HIDDEN_CELLS[i].classList.remove('d-none');
            SHOW_CELLS.innerHTML = 'Hide Cells';
        } else {
            HIDDEN_CELLS[i].classList.add('d-none');
            SHOW_CELLS.innerHTML = 'Show More';
        }
    }
}

SHOW_CELLS.addEventListener('click', display);