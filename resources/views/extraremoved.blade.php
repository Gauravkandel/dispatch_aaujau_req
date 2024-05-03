<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>International Telephone Input</title>
  <link rel="stylesheet" href="{{ asset('telinput/css/intlTelInput.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('telinput/css/demo.css') }}" type="text/css">
</head>

<body>
  <h1>International Telephone Input</h1>
  <form>
    <input id="phone" name="phone" type="tel">
    <button type="submit">Submit</button>
  </form>

  <script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
  <script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
        hiddenInput: "full_number",
      // initialCountry: "auto",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      // preferredCountries: ['cn', 'jp'],
      separateDialCode: true,
      utilsScript: "{{ asset('telinput/js/utils.js') }}",
    });
  </script>
</body>

</html>