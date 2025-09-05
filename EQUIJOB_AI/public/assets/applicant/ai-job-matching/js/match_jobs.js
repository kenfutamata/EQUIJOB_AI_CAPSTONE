    function showFileName() {
      const input = document.getElementById('resume');
      const display = document.getElementById('fileNameDisplay');
      display.textContent = input.files.length > 0 ? `Selected file: ${input.files[0].name}` : '';
    }