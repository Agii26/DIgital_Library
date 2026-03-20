@extends('layouts.public')

@section('title', 'Services — BES Digital Library')
@section('hero-badge', 'What We Offer')
@section('hero-title') Library <em>Services</em> @endsection

@section('extra-styles')
<style>
    .services-intro {
        font-size: 0.95rem; line-height: 1.85;
        color: var(--text-muted);
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border);
    }

    .accordion { display: flex; flex-direction: column; gap: 0.75rem; }

    .accordion-item {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(13,27,46,0.06);
        transition: box-shadow 0.2s;
    }

    .accordion-item:hover { box-shadow: 0 4px 14px rgba(13,27,46,0.1); }

    .accordion-header {
        padding: 1.125rem 1.5rem;
        font-family: var(--font-serif);
        font-size: 1rem; font-weight: 600;
        color: var(--navy);
        cursor: pointer;
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem;
        transition: background 0.18s;
        user-select: none;
        border-left: 3px solid transparent;
    }

    .accordion-header:hover { background: var(--bg); }

    .accordion-header.active {
        background: var(--navy);
        color: #fff;
        border-left-color: var(--gold);
    }

    .accordion-chevron {
        width: 16px; height: 16px;
        flex-shrink: 0;
        transition: transform 0.3s;
        color: var(--gold);
    }

    .accordion-header.active .accordion-chevron { transform: rotate(180deg); }

    .accordion-content {
        max-height: 0; overflow: hidden;
        transition: max-height 0.35s ease, padding 0.3s;
        padding: 0 1.5rem;
        border-left: 3px solid var(--border);
    }

    .accordion-content.open {
        max-height: 400px;
        padding: 1.25rem 1.5rem;
        border-left-color: var(--gold-border);
    }

    .accordion-content p {
        font-size: 0.885rem; line-height: 1.8;
        color: var(--text-muted);
    }

    .accordion-content strong { color: var(--navy); }
</style>
@endsection

@section('content')

<p class="services-intro">
    The BES Library provides essential services to help students, faculty, and staff make the most of library resources.
</p>

<div class="accordion">

    <div class="accordion-item">
        <div class="accordion-header">
            Library Hours &amp; Schedule
            <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="accordion-content">
            <p>Our library is open from <strong>8:00 AM to 5:00 PM</strong>, Monday through Friday. Extended hours may apply during examinations.</p>
        </div>
    </div>

    <div class="accordion-item">
        <div class="accordion-header">
            How to Borrow / Return Books
            <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="accordion-content">
            <p>Students can borrow up to <strong>3 books</strong> for <strong>7 days</strong>. Please return or renew books on time to avoid penalties.</p>
        </div>
    </div>

    <div class="accordion-item">
        <div class="accordion-header">
            How to Have an Account
            <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="accordion-content">
            <p>All enrolled BES students automatically have a library account. Just present your student ID to borrow materials. Faculty accounts are created by the administrator.</p>
        </div>
    </div>

    <div class="accordion-item">
        <div class="accordion-header">
            Digital Library Access
            <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="accordion-content">
            <p>Access our digital collection anytime through your library account at <strong>bes-library.com</strong>. Sign in with the credentials provided by your administrator.</p>
        </div>
    </div>

    <div class="accordion-item">
        <div class="accordion-header">
            Ask a Librarian
            <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="accordion-content">
            <p>Need help finding resources? Visit us in person during library hours or email us at <strong>ramirez@bes-library.com</strong>.</p>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            const isOpen = content.classList.contains('open');

            document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('open'));
            document.querySelectorAll('.accordion-header').forEach(h => h.classList.remove('active'));

            if (!isOpen) {
                content.classList.add('open');
                header.classList.add('active');
            }
        });
    });
</script>
@endsection