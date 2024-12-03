{{-- NOTE: THIS WHOLE PAGE IS INCLUDED IN add_edit_jenisbunga.blade.php!!    (    <div id="appendJenisbungaLevel">    ) --}}
{{-- Show Jenisbunga <select> <option> depending on the chosen selected Section (show the relevant jenisbunga of the chosen section) in append_jenisbunga_level.blade.php page using AJAX --}}
{{-- We created this <div> in a separate file in order for the appendJenisbungaLevel() method inside the JenisbungaController to be able to return the whole file as a response to the AJAX call in admin/js/custom.js to show the proper/relevant jenisbunga <select> box <option> depending on the chosen selected Section --}}

    <div class="form-group">
        <label for="parent_id">Select Jenisbunga Level</label> {{-- The relationship between a jenisbunga and its parent jenisbunga inside the same table i.e. `jenisbunga` table --}}
        <select name="parent_id" id="parent_id" class="form-control"  style="color: #000">
            <option value="0"  @if (isset($jenisbunga['parent_id']) && $jenisbunga['parent_id'] == 0) selected @endif >Main Jenisbunga</option>
            @if (!empty($getJenisbunga))

                {{-- Show the Jenisbunga --}}
                @foreach ($getJenisbunga as $parentJenisbunga) {{-- Show the Jenisbunga --}} {{-- $getJenisbunga are all the parent jenisbunga, and their child jenisbunga --}}
                    @php
                        // echo '<pre>', var_dump($getJenisbunga), '</pre>';
                        // echo '<pre>', var_dump($parentJenisbunga);
                        // echo '<pre>', var_dump($parentJenisbunga['subJenisbunga']);
                    @endphp

                    <option value="{{ $parentJenisbunga['id'] }}"  @if (isset($jenisbunga['parent_id']) && $jenisbunga['parent_id'] == $parentJenisbunga['id']) selected @endif >{{ $parentJenisbunga['jenisbunga_name'] }}</option>

                    {{-- Show the Subjenisbunga --}}
                    @if (!empty($parentJenisbunga['subJenisbunga'])) {{-- Using the hasMany relationship in Jenisbunga.php Model --}}
                        @foreach ($parentJenisbunga['subJenisbunga'] as $subJenisbunga) {{-- Show the Subjenisbunga --}}
                            <option value="{{ $subJenisbunga['id'] }}"  @if (isset($subJenisbunga['parent_id']) && $subJenisbunga['parent_id'] == $subJenisbunga['id']) selected @endif >&nbsp;&raquo;&nbsp;{{ $subJenisbunga['jenisbunga_name'] }}</option> {{-- https://www.w3schools.com/charsets/ref_html_entities_r.asp --}}
                        @endforeach
                    @endif

                @endforeach

            @endif
        </select>
    </div>
