# Form Styling Standards

## Spacing Guidelines

1. **Form Sections**:
   - Margin-bottom: 1.5rem (1.25rem on mobile)
   - Use class: `form-section`
   - Contains related form groups

2. **Form Groups**:
   - Margin-bottom: 1rem
   - Use class: `form-group`
   - Contains label + control pair

3. **Form Controls**:
   - Margin-bottom: 0.5rem
   - Use class: `form-control`

4. **Form Actions**:
   - Margin-top: 2rem (1.5rem on mobile)
   - Use class: `form-actions`
   - Contains submit buttons

## Div Spacing Standards

1. **Form Containers**:
   - Use class: `form-container`
   - Provides outer spacing and structure
   - Contains all form sections

2. **Form Rows**:
   - Use class: `form-row`
   - Contains horizontally aligned form groups
   - Applies consistent padding between elements

3. **Unclassed Divs**:
   - Automatic spacing via CSS rules
   - Margin-bottom: 1.25rem between elements
   - Padding: 0.75rem for nested elements

## Vertical Spacing Standards

### Between Last Input and Submit Button
1. **Standard Forms**:
   - Add `<div class="mb-4"></div>` spacer
   - Creates 1.5rem (24px) gap

2. **Mobile Forms**:
   - Automatically reduces to 1rem (16px)
   - Via responsive CSS rules

### Implementation Example
```html
<!-- After last form field -->
<div class="mb-4"></div>

<!-- Submit button container -->
<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-md w-100">
        Submit
    </button>
</div>
```

## Final Spacing Standards

### Between Last Input and Submit Button
1. **Desktop**: 2.5rem (40px)
   - Implemented via `.form-actions { margin-top: 2.5rem }`
   - Plus `.mb-4` spacer div (1.5rem) before form-actions

2. **Mobile**: 2rem (32px)
   - Implemented via `@media (max-width: 768px) { .form-actions { margin-top: 2rem } }`

### Implementation Pattern
```html
<div class="form-section">
    <!-- Last form group -->
    <div class="form-group">
        <!-- Input field -->
    </div>
    
    <!-- Mandatory spacing element -->
    <div class="mb-4"></div>
</div>

<div class="form-actions">
    <!-- Submit button -->
</div>
```

## Implementation Example
```html
<div class="form-section">
    <div class="form-group">
        <label>Field Label</label>
        <input type="text" class="form-control" name="field_name">
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>

<div class="form-container">
    <div class="form-row">
        <div> <!-- Automatic spacing -->
            <!-- Form content -->
        </div>
    </div>
</div>
