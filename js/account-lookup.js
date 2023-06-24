(function($) {
  $(document).ready(function() {
    const lookupBtn = document.getElementById('lookupBtn');
    lookupBtn.addEventListener('click', () => {
      const ignInput = document.getElementById('ign');
      const tagInput = document.getElementById('tag');
      const ign = ignInput.value;
      const tag = tagInput.value;

      fetch(`https://api.henrikdev.xyz/valorant/v1/account/${ign}/${tag}?force=true`)
        .then(response => response.json())
        .then(data => {
          if (data.status === 200) {
            const resultContainer = document.getElementById('result');
            const region = data.data.region === "ap" ? "Asia Pacific" : data.data.region;

            resultContainer.innerHTML = `
              <div class="flex flex-col md:flex-row items-center md:items-start">
                <div class="md:mr-8 mb-4 md:mb-0">
                  <img src="${data.data.card.small}" alt="Player Card" class="w-48 h-48 rounded-lg">
                </div>
                <div>
                  <h2 class="text-2xl font-bold mb-2">${data.data.name}#${data.data.tag}</h2>
                  <p class="text-gray-600">Account Level: ${data.data.account_level}</p>
                  <p class="text-gray-600">Region: ${region}</p>
                </div>
              </div>
            `;
            
            // Populate custom field value
            const customFieldInput = document.querySelector('input[name="wapf\\[field_6496e37ba13d6\\]"]');
            if (customFieldInput) {
              customFieldInput.value = `${data.data.name}#${data.data.tag}`;
            }

          } else {
            const resultContainer = document.getElementById('result');
            resultContainer.innerHTML = '<p class="text-red-600">Account not found.</p>';

            // Clear custom field value
            const customFieldInput = document.querySelector('input[name="wapf\\[field_6496e37ba13d6\\]"]');
            if (customFieldInput) {
              customFieldInput.value = '';
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
  });
})(jQuery);
