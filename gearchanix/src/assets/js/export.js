function exportTable(format, tableId) {
    switch (format) {
        case 'pdf':
            exportToPDF(tableId);
            break;
        case 'word':
            exportToWord(tableId);
            break;
        case 'excel':
            exportToExcel(tableId);
            break;
        default:
            alert("Invalid format selected.");
    }
}

// PDF export function
function exportToPDF(tableId) {
    const element = document.getElementById(tableId);
    html2pdf()
        .from(element)
        .save(`${tableId}.pdf`)
        .catch(err => console.error('Error exporting to PDF:', err));
}

// Word export function
function exportToWord(tableId) {
    let table = document.getElementById(tableId).outerHTML;
    let blob = new Blob(['\ufeff', table], { type: 'application/msword' });
    let url = URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = `${tableId}.doc`;
    a.click();
    URL.revokeObjectURL(url);
}

// Excel export function
function exportToExcel(tableId) {
    let table = document.getElementById(tableId);
    let workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    XLSX.writeFile(workbook, `${tableId}.xlsx`);
}
