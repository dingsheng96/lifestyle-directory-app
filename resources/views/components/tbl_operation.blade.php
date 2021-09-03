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

            @forelse ($operation_hours as $day)

            <tr>
                <th scope="row">{{ $day->day_name }}</th>
                <td>
                    <div class="icheck-purple">
                        <input type="checkbox" name="operation[{{ $day->days_of_week }}][off_day]" id="off_day_{{ $day->days_of_week }}" {{ old('operation.'.$day->days_of_week.'.off_day', $day->day_off) ? 'checked' : null }}>
                        <label for="off_day_{{ $day->days_of_week }}"></label>
                    </div>
                </td>
                <td>
                    <div class="input-group date timepicker" data-target-input="nearest" id="start_from_{{ $day->days_of_week }}">
                        <input type="text" name="operation[{{ $day->days_of_week }}][start_from]" value="{{ old('operation.'.$day->days_of_week.'.start_from', $day->start ?? '00:00') }}"
                            class="form-control datetimepicker-input @error('operation.'.$day->days_of_week.'.start_from') is-invalid @enderror" data-target="#start_from_{{ $day->days_of_week }}" readonly="readonly">
                        <div class="input-group-append" data-target="#start_from_{{ $day->days_of_week }}" data-toggle="datetimepicker">
                            <span class="input-group-text bg-white"><i class="fas fa-clock"></i></span>
                        </div>
                    </div>
                    @error('operation.'.$day->days_of_week.'.start_from')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </td>
                <td>
                    <div class="input-group date timepicker" data-target-input="nearest" id="end_at_{{ $day->days_of_week }}">
                        <input type="text" name="operation[{{ $day->days_of_week }}][end_at]" value="{{ old('operation.'.$day->days_of_week.'.end_at', $day->end ?? '23:59') }}" class="form-control datetimepicker-input @error('operation.'.$day->days_of_week.'.end_at') is-invalid @enderror"
                            data-target="#end_at_{{ $day->days_of_week }}" readonly="readonly">
                        <div class="input-group-append" data-target="#end_at_{{ $day->days_of_week }}" data-toggle="datetimepicker">
                            <span class="input-group-text bg-white"><i class="fas fa-clock"></i></span>
                        </div>
                    </div>
                    @error('operation.'.$day->days_of_week.'.end_at')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </td>
            </tr>

            @empty

            @foreach (Carbon::getDays() as $index => $day)

            <tr>
                <th scope="row">{{ $day }}</th>
                <td>
                    <div class="icheck-purple">
                        <input type="checkbox" name="operation[{{ $index }}][off_day]" id="off_day_{{ $index }}" {{ old('operation.'.$index.'.off_day') ? 'checked' : null }}>
                        <label for="off_day_{{ $index }}"></label>
                    </div>
                </td>
                <td>
                    <div class="input-group date timepicker" data-target-input="nearest" id="start_from_{{ $index }}">
                        <input type="text" name="operation[{{ $index }}][start_from]" value="{{ old('operation.'.$index.'.start_from', '00:00') }}" class="form-control datetimepicker-input @error('operation.'.$index.'.start_from') is-invalid @enderror" data-target="#start_from_{{ $index }}"
                            readonly="readonly">
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
                        <input type="text" name="operation[{{ $index }}][end_at]" value="{{ old('operation.'.$index.'.end_at', '23:59') }}" class="form-control datetimepicker-input @error('operation.'.$index.'.end_at') is-invalid @enderror" data-target="#end_at_{{ $index }}" readonly="readonly">
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

            @endforelse

        </tbody>

    </table>
</div>