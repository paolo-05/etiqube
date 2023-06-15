const columnCountInput = document.getElementById('column-count-input');
columnCountInput.addEventListener('change', function() {
  const columnCount = parseInt(columnCountInput.value);
  if (columnCount < 1) {
    columnCount = 1;
    columnCountInput.value = 1;
  }
  generateTable(columnCount);
});

function generateTable(columnCount) {
  const template = document.getElementById('table-template');
  const table = template.content.cloneNode(true);

  const thead = table.querySelector('thead');
  const headerRow = thead.querySelector('tr');
  headerRow.innerHTML = ''; // Clear existing header cells

  for (let i = 0; i < columnCount; i++) {
    const headerCell = document.createElement('th');
    headerCell.textContent = i + 1;
    headerRow.appendChild(headerCell);
  }

  const tbody = table.querySelector('tbody');
  tbody.innerHTML = ''; // Clear existing rows

  for (let j = 0; j < 10; j++) {
    const row = document.createElement('tr');

    for (let k = 0; k < columnCount; k++) {
      const cell = document.createElement('td');
      cell.id = `cell-${j + 1}-${k + 1}`;
      const select = createDropdown(cell.id);
      cell.appendChild(select);
      row.appendChild(cell);
    }

    tbody.appendChild(row);
  }

  const tableContainer = document.getElementById('table-container');
  tableContainer.innerHTML = '';
  tableContainer.appendChild(table);
}

function createDropdown(cellId) {
  const select = document.createElement('select');
  select.classList.add('dimension-dropdown');

  const option1 = document.createElement('option');
  option1.value = '1x';
  option1.textContent = '1x';
  select.appendChild(option1);

  const option2 = document.createElement('option');
  option2.value = '2x';
  option2.textContent = '2x';
  select.appendChild(option2);

  const option3 = document.createElement('option');
  option3.value = '3x';
  option3.textContent = '3x';
  select.appendChild(option3);

  select.addEventListener('change', function() {
    mergeCells(cellId);
  });

  return select;
}

function mergeCells(cellId) {
  const table = document.querySelector('.locker');
  const rowCount = table.rows.length;
  const columnCount = table.rows[0].cells.length;

  const cell = document.getElementById(cellId);
  const columnIndex = cell.cellIndex;

  const currentRowIndex = cell.parentNode.rowIndex;
  const currentValue = cell.firstChild.value;

  let startIndex = currentRowIndex;
  let endIndex = currentRowIndex;

  // Find start index
  while (startIndex > 0) {
    const prevCell = table.rows[startIndex - 1].cells[columnIndex];
    if (prevCell.firstChild.value !== currentValue) {
      break;
    }
    startIndex--;
  }

  // Find end index
  while (endIndex < rowCount - 1) {
    const nextCell = table.rows[endIndex + 1].cells[columnIndex];
    if (nextCell.firstChild.value !== currentValue) {
      break;
    }
    endIndex++;
  }

  // Merge cells
  for (let i = startIndex; i <= endIndex; i++) {
    const targetCell = table.rows[i].cells[columnIndex];
    if (i === startIndex) {
      targetCell.rowSpan = endIndex - startIndex + 1;
    } else {
      targetCell.style.display = 'none';
    }
  }
}
