<div class="table-responsive">

    <table id="tbl_oprating_hour" class="table w-100 table-hover">

        <thead>
            <tr>
                <th scope="col">{{ __('labels.days_of_week') }}</th>
                <th scope="col">{{ __('labels.off_day') }}</th>
                <th scope="col">{{ __('labels.start_from') }}</th>
                <th scope="col">{{ __('labels.end_at') }}</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($days_of_week as $index => $day)

            <tr>
                <th scope="row">{{ $day }}</th>
                <td>
                    <div class="icheck-purple">
                        <input type="checkbox" name="operation[{{ $index }}][off_day]" id="off_day_{{ $index }}" {{ old('operation.'.$index.'.off_day', isset($target) && count($target) > 0 ? $target->where('days_of_week', $index)->first()->day_off : null) ? 'checked' : null }}>
                        <label for="off_day_{{ $index }}"></label>
                    </div>
                </td>
                <td>
                    <div class="input-group date timepicker" data-target-input="nearest" id="start_from_{{ $index }}">
                        <input type="text" name="operation[{{ $index }}][start_from]" value="{{ old('operation.'.$index.'.start_from', isset($target) && count($target) > 0 ? $target->where('days_of_week', $index)->first()->start : '00:00') }}"
                            class="form-control datetimepicker-input @error('operation.'.$index.'.start_from') is-invalid @enderror" data-target="#start_from_{{ $index }}" readonly="readonly">
                        <div class="input-group-append" data-target="#start_from_{{ $index }}" data-toggle="datetimepicker">
                            <span class="input-group-text bg-white"><i class="fas fa-clock"></i></span>
                        </div>
                    </div>
                    @error('operation.'.$index.'.start_from')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </td>
                <td>
                    <div class="input-group date timepicker" data-target-input="nearest" id="end_at_{{ $index }}">
                        <input type="text" name="operation[{{ $index }}][end_at]" value="{{ old('operation.'.$index.'.end_at', isset($target) && count($target) > 0 ? $target->where('days_of_week', $index)->first()->end : '23:59') }}"
                            class="form-control datetimepicker-input @error('operation.'.$index.'.end_at') is-invalid @enderror" data-target="#end_at_{{ $index }}" readonly="readonly">
                        <div class="input-group-append" data-target="#end_at_{{ $index }}" data-toggle="datetimepicker">
                            <span class="input-group-text bg-white"><i class="fas fa-clock"></i></span>
                        </div>
                    </div>
                    @error('operation.'.$index.'.end_at')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </td>
            </tr>

            @endforeach

        </tbody>

    </table>

</div>