function showFileName() {
  const input = document.getElementById('resume');
  const display = document.getElementById('fileNameDisplay');
  if (input.files.length > 0) {
    display.textContent = `Selected file: ${input.files[0].name}`;
  } else {
    display.textContent = '';
  }
}