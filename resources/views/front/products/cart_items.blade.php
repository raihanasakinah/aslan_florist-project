{{-- Note: This whole file is 'include'-ed in front/products/cart.blade.php (to allow the AJAX call when updating orders quantities in the Cart) --}}


<!-- Products-List-Wrapper -->
<div class="table-wrapper u-s-m-b-60">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Product Size</th>
                <th>Color</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>


            {{-- We'll place this $total_price inside the foreach loop to calculate the total price of all products in Cart. Check the end of the next foreach loop before @endforeach --}}
            @php $total_price = 0 @endphp

            @foreach ($getCartItems as $key=>$item)
                {{-- $getCartItems is passed in from cart() method in Front/ProductsController.php --}}
                @php
                    $getDiscountAttributePrice = \App\Models\Product::getDiscountAttributePrice(
                        $item['product_id'],
                        $item['size'],
                    ); // from the `products_attributes` table, not the `products` table
                    // dd($getDiscountAttributePrice);
                @endphp

                <tr class="barang-row">
                    {{-- menambahkan checkbox --}}
                    <td>
                        {{-- <input type="checkbox" name="selected_products[]" value="{{ $item['product_id'] }}" class="product-checkbox"> --}}
                        <input type="checkbox" name="selected_products[]" value="{{ $item['product_id'] }}"
                            class="product-checkbox">

                        {{-- <input type="checkbox" class="product-checkbox" data-id="{{ $item['product_id'] }}" data-price="{{ $getDiscountAttributePrice['final_price'] }}" name="selected_products[]" value="{{ $item['product_id'] }}"> --}}

                    </td>
                    <td>
                        <div class="cart-anchor-image">
                            <a href="{{ url('product/' . $item['product_id']) }}">
                                <img src="{{ asset('front/images/product_images/small/' . $item['product']['product_image']) }}"
                                    alt="Product">
                                <h6>
                                    {{ $item['product']['product_name'] }}
                                </h6>
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="cart-code">
                            <div class="code">
                                <h6>{{ $item['product']['product_code'] }} </h6>
                            </div>
                    </div>
                    <td>
                        <div class="cart-size">
                            <div class="size">
                                <h6>{{ $item['size'] }} </h6>
                            </div>
                    </div>
                    <td>
                        <div class="cart-color">
                            <div class="color">
                                <h6>{{ $item['product']['product_color'] }} </h6>
                            </div>
                    </div>
                </td>
                {{-- tambahkan semua yang di product --}}
                    <td>
                        <div class="cart-price">



                            @if ($getDiscountAttributePrice['discount'] > 0)
                                {{-- If there's a discount on the price, show the price before (the original price) and after (the new price) the discount --}}
                                <div class="price-template">
                                    <div class="item-new-price">
                                        Rp {{ $getDiscountAttributePrice['final_price'] }}
                                    </div>
                                    <div class="item-old-price" style="margin-left: -40px">
                                        Rp {{ $getDiscountAttributePrice['product_price'] }}
                                    </div>
                                </div>
                            @else
                                {{-- if there's no discount on the price, show the original price --}}
                                <div class="price-template">
                                    <div class="item-new-price">
                                        Rp {{ $getDiscountAttributePrice['final_price'] }}
                                    </div>
                                </div>
                            @endif



                        </div>
                    </td>
                    <td>
                        <div class="cart-quantity">
                            <div class="quantity">
                                <input type="text" class="quantity-text-field" value="{{ $item['quantity'] }}"
                                    name="quantity[]">
                                <a data-max="1000" class="plus-a  updateCartItem" data-cartid="{{ $item['id'] }}"
                                    data-qty="{{ $item['quantity'] }}">&#43;</a> {{-- The Plus sign:  Increase items by 1 --}}
                                {{-- .updateCartItem CSS class and the Custom HTML attributes data-cartid & data-qty are used to make the AJAX call in front/js/custom.js --}}
                                <a data-min="1" class="minus-a updateCartItem" data-cartid="{{ $item['id'] }}"
                                    data-qty="{{ $item['quantity'] }}">&#45;</a> {{-- The Minus sign: Decrease items by 1 --}}
                                {{-- .updateCartItem CSS class and the Custom HTML attributes data-cartid & data-qty are used to make the AJAX call in front/js/custom.js --}}
                            </div>



                            </script>
                        </div>
                    </td>
                    <td>
                        <div class="cart-price">
                            Rp {{ $getDiscountAttributePrice['final_price'] * $item['quantity'] }}
                            {{-- price of all products (after discount (if any)) (= price (after discoutn) * no. of products) --}}
                        </div>
                    </td>
                    <td>
                        <div class="action-wrapper">
                            {{-- <button class="button button-outline-secondary fas fa-sync"></button> --}}
                            {{-- <button class="button button-outline-secondary fas fa-trash deleteCartItem" data-cartid="{{ $item['id'] }}"></button>.deleteCartItem CSS class and the Custom HTML attribute data-cartid is used to make the AJAX call in front/js/custom.js --}}
                            <button class="btn btn-danger fas fa-trash deleteCartItem"
                                data-cartid="{{ $item['id'] }}"></button>
                        </div>
                    </td>
                </tr>


                {{-- This is placed here INSIDE the foreach loop to calculate the total price of all products in Cart --}}
                @php $total_price = $total_price + ($getDiscountAttributePrice['final_price'] * $item['quantity']) @endphp
            @endforeach
            {{-- <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // console.log("ajfbakvfbuka");

                    // Event listener untuk mengambil produk yang ter-checklist
                    const collectCheckedItems = () => {
                        const checkedItems = [];
                        const checkboxes = document.querySelectorAll(
                            '.product-checkbox:checked'); // Seleksi checkbox yang ter-checklist

                        // Cek jika ada checkbox yang ter-checklist
                        // if (checkboxes.length === 0) {
                        //     console.log('Tidak ada barang yang ter-checklist');
                        //     return checkedItems; // Kembalikan array kosong jika tidak ada barang yang ter-checklist
                        // }

                        checkboxes.forEach((checkbox) => {
                            const row = checkbox.closest('.barang-row'); // Dapatkan baris tabel dari checkbox
                            const productId = checkbox.value; // Nilai produk dari checkbox

                            // Ambil informasi tambahan jika diperlukan
                            const productName = row.querySelector('.cart-anchor-image h6').innerText.trim();
                            const productPrice = parseInt(
                                row.querySelector('.cart-price').innerText.replace(/[^0-9]/g, '')
                            );
                            const quantity = parseInt(row.querySelector('.quantity-text-field').value);
                            const subtotal = productPrice * quantity;

                            // Tambahkan ke array
                            checkedItems.push({
                                id: productId,
                                name: productName,
                                price: productPrice,
                                quantity: quantity,
                                subtotal: subtotal,
                            });
                        });

                        console.log(checkedItems); // Debug: Periksa data yang dikumpulkan di konsol browser
                        return checkedItems;
                    };

                    // Kirim data checkedItems ke server dengan AJAX saat tombol diklik


                });
            </script> --}}


            {{-- <script>
                //ambil total harga
                function updateTotal() {
                    const items = document.querySelectorAll('.barang-row');
                    let total = 0;
                    items.forEach(item => {
                        const checkbox = item.querySelector('.product-checkbox');
                        if (checkbox && checkbox.checked) {
                            const jumlah = parseInt(item.querySelector('.quantity-text-field').value);
                            const harga = parseInt(item.querySelector('.cart-price').innerText.replace(/[^0-9]/g, ''));
                            total += jumlah * harga;
                        }
                    });
                    document.querySelector('.totalHarga').innerText = `Rp${total.toLocaleString()}`;
                    console.log('Jumlah:', jumlah);
                    console.log('Harga:', harga);
                    console.log('Subtotal:', jumlah * harga);

                }


                //end hitung total harga



            </script> --}}



        </tbody>
    </table>
</div>
<!-- Products-List-Wrapper /- -->





{{-- To solve the problem of Submiting the Coupon Code works only once, we moved the Coupon part from cart_items.blade.php to here in cart.blade.php --}} {{-- Explanation of the problem: http://publicvoidlife.blogspot.com/2014/03/on-on-or-event-delegation-explained.html --}}





<!-- Billing -->
<div class="calculation u-s-m-b-60">
    <div class="table-wrapper-2">
        <table>
            <thead>
                <tr>
                    <th colspan="2">Cart Totals</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <h3 class="calc-h3 u-s-m-b-0">Sub Total</h3> {{-- Total Price before any Coupon discounts --}}
                    </td>
                    <td>
                        {{-- <span id="total-price">0</span> --}}
                        <span class="totalHarga">Rp 0</span>
                        {{-- <span class="calc-text">Rp{{ $total_price }}</span> --}}
                        {{-- <span class="calc-text" id="'totalHarga">0</span> --}}

                    </td>
                </tr>
                <tr>
                    <td>
                        <h3 class="calc-h3 u-s-m-b-0">Coupon Discount</h3>
                    </td>
                    <td>
                        <span class="calc-text couponAmount"> {{-- We create the 'couponAmount' CSS class to use it as a handle for AJAX inside    $('#applyCoupon').submit();    function in front/js/custom.js --}}

                            @if (\Illuminate\Support\Facades\Session::has('couponAmount'))
                                {{-- We stored the 'couponAmount' in a Session Variable inside the applyCoupon() method in Front/ProductsController.php --}}
                                Rp {{ \Illuminate\Support\Facades\Session::get('couponAmount') }}
                            @else
                                Rp 0
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3 class="calc-h3 u-s-m-b-0">Grand Total</h3> {{-- Total Price after Coupon discounts (if any) --}}
                    </td>
                    <td>
                        <span class="totalHarga">Rp 0</span>
                        {{-- <span class="calc-text grand_total">Rp{{ $total_price - \Illuminate\Support\Facades\Session::get('couponAmount') }}</span> --}}
                        {{-- We create the 'grand_total' CSS class to use it as a handle for AJAX inside    $('#applyCoupon').submit();    function in front/js/custom.js --}} {{-- We stored the 'couponAmount' a Session Variable inside the applyCoupon() method in Front/ProductsController.php --}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Billing /- -->
