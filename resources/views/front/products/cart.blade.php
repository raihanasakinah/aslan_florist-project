{{-- Note: cart.blade.php is the page that opens when you ... --}}
@extends('front.layout.layout')


@section('content')
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Cart</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="cart.html">Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <!-- Cart-Page -->
    <div class="page-cart u-s-p-t-80">
        <div class="container">



            {{-- Displaying The Validation Errors: https://laravel.com/docs/9.x/validation#quick-displaying-the-validation-errors AND https://laravel.com/docs/9.x/blade#validation-errors --}}
            {{-- Determining If An Item Exists In The Session (using has() method): https://laravel.com/docs/9.x/session#determining-if-an-item-exists-in-the-session --}}
            {{-- Our Bootstrap success message in case of updating admin password is successful: --}}
            {{-- Displaying Success Message --}}
            @if (Session::has('success_message'))
                <!-- Check vendorRegister() method in Front/VendorController.php -->
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success:</strong> {{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{-- Displaying Error Messages --}}
            @if (Session::has('error_message'))
                <!-- Check vendorRegister() method in Front/VendorController.php -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> {{ Session::get('error_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{-- Displaying Error Messages --}}
            @if ($errors->any())
                <!-- Check vendorRegister() method in Front/VendorController.php -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> @php echo implode('', $errors->all('<div>:message</div>')); @endphp
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif



            <div class="row">
                <div class="col-lg-12">



                    <form action="{{ url('/checkout-temp') }}" method="post">
                        @csrf
                        <div id="appendCartItems"> {{-- We 'include'-ed this file to allow the AJAX call in front/js/custom.js when updating orders quantities in the Cart --}}
                            @include('front.products.cart_items')
                        </div>





                        {{-- To solve the problem of Submiting the Coupon Code works only once, we moved the Coupon part from cart_items.blade.php to here in cart.blade.php --}} {{-- Explanation of the problem: http://publicvoidlife.blogspot.com/2014/03/on-on-or-event-delegation-explained.html --}}
                        <!-- Coupon -->
                        <div class="coupon-continue-checkout u-s-m-b-60">
                            <div class="coupon-area">
                                <h6>Enter your coupon code if you have one.</h6>
                                <div class="coupon-field">



                                    {{-- Note: For Coupons, user must be logged in (authenticated) to be able to redeem them. Both 'admins' and 'vendors' can add Coupons. Coupons added by 'vendor' will be available for their products ONLY, but ones added by 'admins' will be available for ALL products. --}}

                                    <form id="applyCoupon" method="post" action="javascript:void(0)"
                                        @if (\Illuminate\Support\Facades\Auth::check()) user=1 @endif> {{-- Created an id for this <form> to use it as a handle in jQuery for submission via AJAX. Check front/js/custom.js --}}
                                        {{-- Only logged in (authenticated) users can redeem the coupon, so we make a condition, if the user is logged in (authenticated), we create that Custom HTML attribute 'user = 1' so that jQuery can use it to submit the form. Check front/js/custom.js --}} {{-- Note: We need to deactivate the 'action' HTML attribute (using    action="javascript:void(0)"    ) as we'r going to submit using an AJAX call. Check front/js/custom.js --}}
                                        <label class="sr-only" for="coupon-code">Apply Coupon</label>
                                        <input type="text" class="text-field" placeholder="Enter Coupon Code"
                                            id="code" name="code">
                                        <button type="submit" class="button">Apply Coupon</button>
                                    </form>



                                </div>
                            </div>
                            <div class="button-area">
                                <a href="{{ url('/') }}" class="continue">Continue Shopping</a>
                                {{-- <a href="{{ url('/checkout') }}" class="checkout">Proceed to Checkout</a> --}}
                                {{-- <a href="javascript:void(0);" id="checkout" class="checkout">Proceed to Checkout</a> --}}
                                <button type="submit" class="checkout">Proceed to Checkout</button>

                    </form>
                </div>
            </div>
            <!-- Coupon /- -->

            <script>
                // document.querySelector('#checkout')?.addEventListener('click', function() {
                //     const selectedItems = collectCheckedItems(); // Mengumpulkan data produk yang dicentang

                //     if (selectedItems.length > 0) {
                //         // Mengirim data menggunakan AJAX
                //         fetch('{{ url('/checkout') }}', {
                //                 method: 'POST',
                //                 headers: {
                //                     'Content-Type': 'application/json',
                //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                //                         'content') // Menyertakan CSRF Token
                //                 },
                //                 body: JSON.stringify({
                //                     checkedItems: selectedItems // Mengirimkan data produk yang dipilih ke server
                //                 })
                //             })
                //             .then(response => response.json())
                //             .then(data => {
                //                 console.log('Data terkirim dengan sukses:', data);
                //                 // Arahkan ke halaman checkout setelah berhasil mengirim data
                //                 window.location.href = '{{ url('/checkout') }}';
                //             })
                //             .catch(error => {
                //                 console.error('Error saat mengirim data:', error);
                //             });
                //     } else {
                //         alert("Tidak ada produk yang ter-checklist.");
                //     }
                // });
                function collectCheckedItems() {
                    const checkedItems = [];
                    const checkboxes = document.querySelectorAll('.product-checkbox:checked');

                    checkboxes.forEach((checkbox) => {
                        const row = checkbox.closest('.barang-row'); // Dapatkan baris tabel dari checkbox
                        const productId = checkbox.value; // Nilai produk dari checkbox

                        // Ambil informasi tambahan jika diperlukan
                        const productName = row.querySelector('.cart-anchor-image h6').innerText.trim();
                        const productPrice = parseInt(
                            row.querySelector('.cart-price').innerText.replace(/[^0-9]/g, '')
                        );
                        const code = row.querySelector('.cart-code h6').innerText;
                        const size = row.querySelector('.cart-size h6').innerText;
                        const color = row.querySelector('.cart-color h6').innerText;
                        const quantity = parseInt(row.querySelector('.quantity-text-field').value);
                        const subtotal = productPrice * quantity;

                        checkedItems.push({
                            id: productId,
                            name: productName,
                            code: code,
                            size: size,
                            color: color,
                            price: productPrice,
                            quantity: quantity,
                            subtotal: subtotal,
                        });
                    });

                    console.log("checkklis", checkedItems); // Debug: Periksa data yang dikumpulkan di konsol browser
                    return checkedItems;
                }

                // Event listener untuk tombol checkout
                document.querySelector('#checkout')?.addEventListener('click', function() {
                    const selectedItems = collectCheckedItems();
                    const queryString = encodeURIComponent(JSON.stringify(selectedItems));
                    window.location.href = `/checkout?selectedItems=${queryString}`;

                    // if (selectedItems.length > 0) {
                    //     fetch('{{ url('/checkout') }}', {
                    //             method: 'POST',
                    //             headers: {
                    //                 'Content-Type': 'application/json',
                    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                    //                     .getAttribute('content')
                    //             },
                    //             body: JSON.stringify({
                    //                 checkedItems: selectedItems // Kirimkan data ke server
                    //             })
                    //         })
                    //         .then(response => response.json())
                    //         .then(data => {
                    //             console.log('Data terkirim dengan sukses:', data);
                    //         })
                    //         .catch(error => {
                    //             console.error('Error saat mengirim data:', error);
                    //         });
                    // } else {
                    //     alert("Tidak ada produk yang ter-checklist.");
                    // }
                });
            </script>



        </div>
    </div>
    </div>
    </div>
    <!-- Cart-Page /- -->
@endsection
