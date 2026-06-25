// aics_form.js - Handles dynamic family composition rows
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('famcom-tbody');
    const btnAdd = document.getElementById('btn-add-famcom');

    let rowIdx = 0; // Starts at 0, then increments on each add

    function createRow(index) {
        const tr = document.createElement('tr');
        tr.id = `famcom-row-${index}`;
        tr.innerHTML = `
            <td><input type="text" name="famcom[${index}][first_name]" class="famcom-input" placeholder="First Name" required></td>
            <td><input type="text" name="famcom[${index}][middle_name]" class="famcom-input" placeholder="M.I."></td>
            <td><input type="text" name="famcom[${index}][last_name]" class="famcom-input" placeholder="Last Name" required></td>
            <td><input type="number" name="famcom[${index}][age]" class="famcom-input" style="width: 60px;" placeholder="Age" required min="0" max="130"></td>
            <td>
                <select name="famcom[${index}][sex]" class="famcom-input" style="width: 80px;" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td>
                <select name="famcom[${index}][civil_status]" class="famcom-input" required>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Widowed">Widowed</option>
                    <option value="Separated">Separated</option>
                </select>
            </td>
            <td><input type="text" name="famcom[${index}][educational_attainment]" class="famcom-input" placeholder="Education"></td>
            <td><input type="text" name="famcom[${index}][occupation]" class="famcom-input" placeholder="Occupation"></td>
            <td><input type="text" name="famcom[${index}][income]" class="famcom-input" placeholder="Income (e.g. 5,000)"></td>
            <td style="text-align: center;">
                <button type="button" class="btn-remove-row material-symbols-outlined" style="font-size: 16px;">delete</button>
            </td>
        `;

        // Handle row delete
        tr.querySelector('.btn-remove-row').addEventListener('click', () => {
            tr.remove();
        });

        return tr;
    }

    // Add initial empty row
    if (tableBody) {
        tableBody.appendChild(createRow(rowIdx++));
    }

    if (btnAdd && tableBody) {
        btnAdd.addEventListener('click', () => {
            tableBody.appendChild(createRow(rowIdx++));
        });
    }
});
