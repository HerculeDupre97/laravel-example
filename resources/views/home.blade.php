<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
        }

        .left-container {
            flex: 1;
            margin-right: 20px;
        }

        .right-container {
            flex: 1;
        }

        table {
            width: 100%;
        }
    </style>
</head>
<body>

<h1>Currency Rates</h1>

<div class="container">
    <div class="left-container">
        <button id="fetch-rates">Fetch Latest Rates</button>
        <table id="rates-table" border="1">
            <thead>
                <tr>
                    <th>Currency Pair</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data->forex))
                    @foreach ($data->forex as $key => $value)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="right-container">
    <h2>Convert Currency</h2>
    <form id="convert-form">
        <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
        <select id="from-currency" name="from_currency">
            @foreach ($currencies as $currency)
                <option value="{{ $currency }}">{{ $currency }}</option>
            @endforeach
        </select>
        <select id="to-currency" name="to_currency">
            @foreach ($currencies as $currency)
                <option value="{{ $currency }}">{{ $currency }}</option>
            @endforeach
        </select>
        <button type="button" id="convert-button">Convert</button>
    </form>

    <div id="conversion-result" style="margin-top: 20px;"></div>
</div>
</div>

    <script>
        $(document).ready(function() {
            $('#fetch-rates').click(function() {
                $.ajax({
                    url: '/latest-rates',
                    method: 'GET',
                    success: function(response) {
                        $('#rates-table tbody').empty();
                        $.each(response.forex, function(key, value) {
                            $('#rates-table tbody').append('<tr><td>' + key + '</td><td>' + value + '</td></tr>');
                        });
                        $('#rates-table').show();
                    },
                    error: function(err) {
                        console.error(err)
                        alert('Error fetching latest rates');
                    }
                });
            });

            $('#convert-button').click(function() {
                var amount = $('#amount').val();
                var fromCurrency = $('#from-currency').val();
                var toCurrency = $('#to-currency').val();

                $.ajax({
                    url: '/convert-currency',
                    method: 'POST',
                    data: {
                        amount: amount,
                        from_currency: fromCurrency,
                        to_currency: toCurrency,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.error) {
                            $('#conversion-result').html('<p style="color: red;">' + response.error + '</p>');
                        } else {
                            $('#conversion-result').html('<p>Converted Amount: ' + response.convertedAmount + ' at rate ' + response.rate + '</p>');
                        }
                    },
                    error: function() {
                        alert('Error converting currency');
                    }
                });
            });
        });
    </script>
</body>
</html>
