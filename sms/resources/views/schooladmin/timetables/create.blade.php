@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Add New Timetable Entry</h3>
                <a href="{{ route('schooladmin.timetables.index') }}" class="btn btn-secondary">Back to List</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('schooladmin.timetables.store') }}" method="POST">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="class_level_id">Class Level</label>
                                <select id="class_level_id" name="class_level_id" class="form-control" required>
                                    <option value="">Select Class Level</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_level_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="section_id">Section</label>
                                <select id="section_id" name="section_id" class="form-control" required>
                                    <option value="">Select Class First</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subject_id">Subject</label>
                                <select name="subject_id" class="form-control" required>
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id">Teacher</label>
                                <select name="teacher_id" class="form-control" required>
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="weekday">Day</label>
                                <select name="weekday" class="form-control" required>
                                    <option value="">Select Day</option>
                                    @foreach($weekdays as $day)
                                        <option value="{{ $day }}" {{ old('weekday') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="start_time">Start Time</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end_time">End Time</label>
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Entry</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    // To Wait for the DOM to fully load before running the script
    document.addEventListener('DOMContentLoaded', function() {
        
        const classSelect = document.getElementById('class_level_id');
        const sectionSelect = document.getElementById('section_id');
        
        // Load the class data ONCE when the page loads
        const allClassData = @json($classes); 
        
        // Get the old section ID (if validation failed) safely as a string
        const oldSectionId = "{{ old('section_id') }}";

        // Function to update the section dropdown
        function updateSections() {
            const selectedClassId = classSelect.value;
            
            // Clear current options
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (!selectedClassId) return;

            // Find the selected class object
            const selectedClass = allClassData.find(c => c.id == selectedClassId);

            if (selectedClass && selectedClass.sections.length > 0) {
                selectedClass.sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.text = section.name;

                    //Check if this section was previously selected (using loose equality for string/number match)
                    if (oldSectionId == section.id) {
                        option.selected = true;
                    }
                    
                    sectionSelect.appendChild(option);
                });
            } else {
                sectionSelect.innerHTML = '<option value="">No sections found</option>';
            }
        }

        // Attach event listener
        classSelect.addEventListener('change', updateSections);

        // Trigger immediately if a class is already selected (e.g., after validation error or "Back" button)
        if (classSelect.value) {
            updateSections();
        }
    });
</script>
@endsection