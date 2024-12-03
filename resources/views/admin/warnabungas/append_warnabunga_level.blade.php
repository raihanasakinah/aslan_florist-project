{{-- NOTE: THIS WHOLE PAGE IS INCLUDED IN add_edit_warnabunga.blade.php!!    (    <div id="appendWarnabungaLevel">    ) --}}
{{-- Show Warnabunga <select> <option> depending on the chosen selected Section (show the relevant warnabunga of the chosen section) in append_warnabunga_level.blade.php page using AJAX --}}
{{-- We created this <div> in a separate file in order for the appendWarnabungaLevel() method inside the WarnabungaController to be able to return the whole file as a response to the AJAX call in admin/js/custom.js to show the proper/relevant warnabunga <select> box <option> depending on the chosen selected Section --}}

    <div class="form-group">
        <label for="parent_id">Select Warnabunga Level</label> {{-- The relationship between a warnabunga and its parent warnabunga inside the same table i.e. `warnabunga` table --}}
        <select name="parent_id" id="parent_id" class="form-control"  style="color: #000">
            <option value="0"  @if (isset($warnabunga['parent_id']) && $warnabunga['parent_id'] == 0) selected @endif >Main Warnabunga</option>
            @if (!empty($getWarnabunga))

                {{-- Show the Warnabunga --}}
                @foreach ($getWarnabunga as $parentWarnabunga) {{-- Show the Warnabunga --}} {{-- $getWarnabunga are all the parent warnabunga, and their child warnabunga --}}
                    @php
                        // echo '<pre>', var_dump($getWarnabunga), '</pre>';
                        // echo '<pre>', var_dump($parentWarnabunga);
                        // echo '<pre>', var_dump($parentWarnabunga['subWarnabunga']);
                    @endphp

                    <option value="{{ $parentWarnabunga['id'] }}"  @if (isset($warnabunga['parent_id']) && $warnabunga['parent_id'] == $parentWarnabunga['id']) selected @endif >{{ $parentWarnabunga['warnabunga_name'] }}</option>

                    {{-- Show the Subwarnabunga --}}
                    @if (!empty($parentWarnabunga['subWarnabunga'])) {{-- Using the hasMany relationship in Warnabunga.php Model --}}
                        @foreach ($parentWarnabunga['subWarnabunga'] as $subWarnabunga) {{-- Show the Subwarnabunga --}}
                            <option value="{{ $subWarnabunga['id'] }}"  @if (isset($subWarnabunga['parent_id']) && $subWarnabunga['parent_id'] == $subWarnabunga['id']) selected @endif >&nbsp;&raquo;&nbsp;{{ $subWarnabunga['warnabunga_name'] }}</option> {{-- https://www.w3schools.com/charsets/ref_html_entities_r.asp --}}
                        @endforeach
                    @endif

                @endforeach

            @endif
        </select>
    </div>
