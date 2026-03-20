@extends('layouts.public')

@section('title', 'About — BES Digital Library')
@section('hero-badge', 'Who We Are')
@section('hero-title') About <em>Us</em> @endsection

@section('extra-styles')
<style>
    .about-intro {
        font-size: 0.95rem; line-height: 1.85;
        color: var(--text-muted);
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border);
    }

    .about-intro strong { color: var(--navy); }

    .about-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 4px rgba(13,27,46,0.06);
        transition: box-shadow 0.2s, transform 0.2s;
        position: relative;
        overflow: hidden;
    }

    .about-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 3px; height: 100%;
        background: linear-gradient(180deg, var(--gold), var(--gold-light));
    }

    .about-card:hover {
        box-shadow: 0 6px 18px rgba(13,27,46,0.1);
        transform: translateY(-2px);
    }

    .about-card h3 {
        font-family: var(--font-serif);
        font-size: 1.1rem; font-weight: 600;
        color: var(--navy); margin-bottom: 0.75rem;
    }

    .about-card p, .about-card ul {
        font-size: 0.885rem; line-height: 1.8;
        color: var(--text-muted);
    }

    .about-card ul { padding-left: 1.25rem; }
    .about-card ul li { margin-bottom: 0.4rem; }
    .about-card b { color: var(--navy); }
    .about-card p + p { margin-top: 0.875rem; }

    .vm-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .vm-card {
        background: var(--navy);
        border-radius: 10px;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .vm-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
    }

    .vm-card h3 {
        font-family: var(--font-serif);
        font-size: 0.72rem; font-weight: 600;
        color: var(--gold);
        text-transform: uppercase; letter-spacing: 0.12em;
        margin-bottom: 0.625rem;
    }

    .vm-card p {
        font-size: 0.9rem;
        color: rgba(200,220,245,0.85);
        line-height: 1.75;
    }

    @media (max-width: 600px) {
        .vm-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<p class="about-intro">
    The <strong>Bulacan Ecumenical School Library</strong> is dedicated to providing resources, services, and learning spaces that support the academic and personal growth of students, faculty, and staff.
    Our collection includes books, journals, e-resources, and multimedia materials designed to help users in their research and learning journey.
    We aim to create a welcoming environment where knowledge is accessible, learning is encouraged, and curiosity is celebrated.
</p>

<div class="about-card">
    <h3>Philosophical Mandate</h3>
    <p>
        BULACAN ECUMENICAL SCHOOL focuses its educational mandate on the <b>HOLISTIC TRANSFORMATION of STUDENTS</b>.
        This program is integrated with the educational system prescribed by the Department of Education.
        We believe that every human being, created by God with unique potentials, deserves the chance to harness and develop these potentials to serve their God-given purpose through a system of <b>Transformative Education</b>.
    </p>
    <p>
        These potentials manifest through <b>Multiple Intelligences</b> and must be nurtured across the
        <b>Cognitive, Affective, Physical, Social, and Spiritual domains</b> in alignment with Christian values.
        We believe that when a child's total well-being is carefully and purposively addressed, their God-given potentials will emerge and serve their purpose.
    </p>
</div>

<div class="vm-grid">
    <div class="vm-card">
        <h3>Vision</h3>
        <p>Home of God-fearing achievers, Agent of values transformation.</p>
    </div>
    <div class="vm-card">
        <h3>Mission</h3>
        <p>BES is committed to provide Quality Education upholding Christian Values.</p>
    </div>
</div>

<div class="about-card">
    <h3>Core Values</h3>
    <ul>
        <li>Spiritual Discipline</li>
        <li>Valuing People</li>
        <li>Stewardship</li>
        <li>Integrity</li>
    </ul>
</div>

<div class="about-card">
    <h3>Goals</h3>
    <p>
        Maintain an institution that supports the development of character, positive values, faith in God,
        and facilitates every opportunity to enhance knowledge, skills, and the use of technology for social and global readiness.
    </p>
</div>

<div class="about-card">
    <h3>Objectives</h3>
    <ul>
        <li>Deliver a curriculum that addresses the holistic needs of learners through classroom and outdoor engagement.</li>
        <li>Promote exploratory and discovery learning through the aid of laboratories.</li>
        <li>Establish and make available facilities for the advancement of capabilities and competencies of all stakeholders.</li>
    </ul>
</div>

@endsection