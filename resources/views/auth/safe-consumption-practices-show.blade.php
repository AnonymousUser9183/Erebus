@extends('layouts.auth')

@section('title', 'Safe Consumption Practices - Harm Reduction Guide')

@section('breadcrumb', 'Safe Consumption Practices')

@section('content')
<style>
    .safe-consumption-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .safe-consumption-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .safe-consumption-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .safe-consumption-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .safe-consumption-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .safe-consumption-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .safe-consumption-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .safe-consumption-section p {
        color: #666666;
        line-height: 1.8;
        margin-bottom: 16px;
        font-size: 15px;
    }

    .info-box {
        background-color: #f0f9fb;
        border-left: 4px solid #2a9db8;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .info-box.warning {
        background-color: #fef3c7;
        border-left-color: #f59e0b;
    }

    .info-box.danger {
        background-color: #fee2e2;
        border-left-color: #dc2626;
    }

    .info-box.success {
        background-color: #dcfce7;
        border-left-color: #22c55e;
    }

    .info-box strong {
        color: #1a1a1a;
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .info-box p {
        margin: 0;
        font-size: 14px;
        color: #333333;
    }

    .safe-consumption-section ul,
    .safe-consumption-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .safe-consumption-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .practices-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .practice-card {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
    }

    .practice-card h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .practice-card ul {
        margin-left: 0;
        padding-left: 20px;
        margin-bottom: 0;
    }

    .practice-card li {
        margin-bottom: 8px;
        font-size: 14px;
    }

    .checklist-box {
        background-color: #f0f9fb;
        border: 2px solid #2a9db8;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .checklist-box h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 16px 0;
        font-weight: 600;
    }

    .checklist-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 12px;
        font-size: 14px;
        color: #666666;
        line-height: 1.5;
    }

    .checklist-item:last-child {
        margin-bottom: 0;
    }

    .checklist-box input[type="checkbox"] {
        margin-right: 12px;
        margin-top: 2px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .route-comparison {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    .route-comparison th {
        background-color: #0d5b7c;
        color: white;
        padding: 16px;
        text-align: left;
        font-weight: 600;
    }

    .route-comparison td {
        padding: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .route-comparison tr:hover {
        background-color: #f5f5f5;
    }

    .danger-substances {
        background-color: #fee2e2;
        border: 2px solid #dc2626;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .danger-substances h4 {
        color: #dc2626;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .disclaimer-section {
        border: 2px solid #dc2626;
        background-color: #fee2e2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .safe-consumption-header h1 {
            font-size: 24px;
        }

        .safe-consumption-header p {
            font-size: 14px;
        }

        .safe-consumption-section {
            padding: 16px;
        }

        .safe-consumption-section h2 {
            font-size: 20px;
        }

        .practices-grid {
            grid-template-columns: 1fr;
        }

        .route-comparison {
            font-size: 13px;
        }

        .route-comparison th,
        .route-comparison td {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .safe-consumption-header h1 {
            font-size: 20px;
        }

        .safe-consumption-section {
            padding: 12px;
        }

        .safe-consumption-section h2 {
            font-size: 18px;
        }

        .safe-consumption-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }
    }
</style>

<div class="safe-consumption-page">
    <!-- Header -->
    <div class="safe-consumption-header">
        <h1>🛡️ Safe Consumption Practices</h1>
        <p>Comprehensive harm reduction guide to minimize risks associated with substance use.</p>
    </div>

    <!-- Introduction -->
    <section class="safe-consumption-section">
        <h2>Safe Consumption Overview</h2>
        <p>While the safest choice is abstinence, harm reduction recognizes that some people use substances. These evidence-based practices reduce the serious risks associated with substance use, including overdose, infection, and other health complications.</p>
        
        <div class="info-box success">
            <strong>✓ Harm Reduction Principle</strong>
            <p>Harm reduction acknowledges the reality of substance use while prioritizing health, dignity, and safety. These practices are proven to reduce overdose deaths, transmission of blood-borne infections, and other serious harms.</p>
        </div>
    </section>

    <!-- Before Using -->
    <section class="safe-consumption-section">
        <h2>Before Using: Preparation & Planning</h2>
        
        <div class="practices-grid">
            <div class="practice-card">
                <h4>🧪 Test Your Substance</h4>
                <ul>
                    <li>Use fentanyl test strips</li>
                    <li>Use reagent test kits (Marquis, Mecke)</li>
                    <li>Check for known adulterants</li>
                    <li>Be aware test limitations</li>
                </ul>
            </div>
            <div class="practice-card">
                <h4>👥 Never Use Alone</h4>
                <ul>
                    <li>Have a trusted person present</li>
                    <li>Or use supervised consumption site</li>
                    <li>Tell someone where you are</li>
                    <li>Keep phone accessible</li>
                </ul>
            </div>
            <div class="practice-card">
                <h4>💉 Prepare Equipment</h4>
                <ul>
                    <li>Use sterile equipment only</li>
                    <li>New needle and syringe each time</li>
                    <li>New filter and cooker</li>
                    <li>Clean water and alcohol pads</li>
                </ul>
            </div>
            <div class="practice-card">
                <h4>🆘 Have Safety Ready</h4>
                <ul>
                    <li>Naloxone (Narcan) available</li>
                    <li>Know how to use it</li>
                    <li>Phone ready for 911</li>
                    <li>Safe, clean environment</li>
                </ul>
            </div>
            <div class="practice-card">
                <h4>🤔 Assess Your State</h4>
                <ul>
                    <li>Not intoxicated before starting</li>
                    <li>Physical health checked</li>
                    <li>Mental state stable</li>
                    <li>No mixing substances planned</li>
                </ul>
            </div>
            <div class="practice-card">
                <h4>📋 Document Batches</h4>
                <ul>
                    <li>Note substance appearance</li>
                    <li>Record test results</li>
                    <li>Document source if safe</li>
                    <li>Track batch variations</li>
                </ul>
            </div>
        </div>

        <div class="checklist-box">
            <h4>✓ Pre-Use Safety Checklist</h4>
            <label class="checklist-item">
                <input type="checkbox" name="test">
                <span>I have tested my substance with appropriate test kit</span>
            </label>
            <label class="checklist-item">
                <input type="checkbox" name="notalone">
                <span>I have a trusted person with me or nearby</span>
            </label>
            <label class="checklist-item">
                <input type="checkbox" name="equipment">
                <span>I have sterile equipment that is unopened/unused</span>
            </label>
            <label class="checklist-item">
                <input type="checkbox" name="narcan">
                <span>Naloxone is available and accessible</span>
            </label>
            <label class="checklist-item">
                <input type="checkbox" name="safe">
                <span>I am in a safe, clean environment</span>
            </label>
            <label class="checklist-item">
                <input type="checkbox" name="sober">
                <span>I am sober and making clear decisions</span>
            </label>
        </div>
    </section>

    <!-- During Use -->
    <section class="safe-consumption-section">
        <h2>During Use: Safe Consumption Techniques</h2>
        
        <h3>Universal Practices:</h3>
        <ul>
            <li><strong>Start Low, Go Slow:</strong> Begin with a very small amount (test dose). Wait to assess effects before using more</li>
            <li><strong>Monitor Continuously:</strong> Remain aware of your physical state. Watch for signs of overdose in yourself and others</li>
            <li><strong>Stay Alert:</strong> Do not heavily intoxicate yourself. Maintain some awareness for emergencies</li>
            <li><strong>Avoid Mixing:</strong> Never combine substances. Interactions can be fatal and unpredictable</li>
            <li><strong>Use Good Technique:</strong> Proper technique reduces injury and infection risk significantly</li>
        </ul>

        <h3>Route-Specific Safety</h3>
        
        <table class="route-comparison">
            <thead>
                <tr>
                    <th>Route</th>
                    <th>Primary Risks</th>
                    <th>Safety Measures</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Injection</strong></td>
                    <td>Infection, abscesses, vein damage, overdose</td>
                    <td>Sterile equipment, clean injection site, proper technique, wound care, rotation of sites</td>
                </tr>
                <tr>
                    <td><strong>Smoking/Inhalation</strong></td>
                    <td>Lung damage, burns, impure substances</td>
                    <td>Clean pipe/equipment, heat source control, proper ventilation, avoid sharing</td>
                </tr>
                <tr>
                    <td><strong>Intranasal (Snorting)</strong></td>
                    <td>Nasal tissue damage, rapid absorption, overdose</td>
                    <td>Clean bills/straws, nasal hygiene, smaller amounts, alternate nostrils</td>
                </tr>
                <tr>
                    <td><strong>Oral</strong></td>
                    <td>Slower absorption, overdose if doses misjudged</td>
                    <td>Known dosing, no mixing with alcohol/CNS depressants, safe setting</td>
                </tr>
            </tbody>
        </table>

        <div class="info-box warning">
            <strong>⚠️ Fentanyl-Contaminated Substances</strong>
            <p>Fentanyl is extremely potent (50-100x morphine). Even tiny amounts can cause overdose. A positive fentanyl test means significantly reduced use is essential. Consider using fentanyl-specific naloxone dosing information.</p>
        </div>
    </section>

    <!-- After Use -->
    <section class="safe-consumption-section">
        <h2>After Use: Recovery & Wound Care</h2>
        
        <h3>Immediate Post-Use:</h3>
        <ul>
            <li>Remain in safe location with your companion</li>
            <li>Monitor for signs of overdose for at least 30 minutes</li>
            <li>Stay hydrated - drink water gradually</li>
            <li>Do not attempt driving or operating machinery</li>
            <li>Keep naloxone nearby in case of delayed overdose</li>
        </ul>

        <h3>Injection Site Care:</h3>
        <ul>
            <li><strong>Clean Wound:</strong> Wash injection site with soap and warm water</li>
            <li><strong>Monitor Site:</strong> Watch for signs of infection (warmth, redness, swelling, pus)</li>
            <li><strong>Rotation:</strong> Rotate injection sites to prevent damage to any single area</li>
            <li><strong>Professional Care:</strong> If infection appears, seek medical help immediately</li>
            <li><strong>Abscess Prevention:</strong> Proper sterile technique prevents serious abscesses</li>
        </ul>

        <h3>Equipment Disposal:</h3>
        <ul>
            <li>Never reuse needles or syringes</li>
            <li>Place used equipment in rigid container (sharps container)</li>
            <li>Dispose according to local regulations (many pharmacies accept sharps)</li>
            <li>Never flush down toilet or leave for others to find</li>
            <li>Ask local harm reduction programs about needle disposal options</li>
        </ul>

        <div class="info-box success">
            <strong>✓ Wound Infection Prevention</strong>
            <p>Most injection-related infections are preventable through: sterile equipment use, proper injection site cleaning, good injection technique, and regular site monitoring. Seek medical care immediately for signs of infection.</p>
        </div>
    </section>

    <!-- Dangerous Combinations -->
    <section class="safe-consumption-section">
        <h2>Dangerous Substance Combinations</h2>
        
        <div class="danger-substances">
            <h4>🚨 Avoid These Combinations</h4>
            <ul>
                <li><strong>Opioids + Benzodiazepines:</strong> Extreme overdose risk. Fatal combination responsible for thousands of deaths annually</li>
                <li><strong>Opioids + Alcohol:</strong> Respiratory depression risk. Can cause overdose death</li>
                <li><strong>Stimulants + Depressants:</strong> Unpredictable effects. Puts strain on heart</li>
                <li><strong>Multiple Opioids:</strong> Overdose risk increases dramatically</li>
                <li><strong>Any Substance + Xylazine:</strong> Xylazine (tranq) does not respond to naloxone. Creates severe overdose risk</li>
                <li><strong>Stimulants (High Doses):</strong> Heart attack, stroke risk increases with higher doses</li>
            </ul>
        </div>

        <div class="info-box danger">
            <strong>🚨 Fentanyl Warning</strong>
            <p>Fentanyl drastically increases overdose risk in ALL combinations. A substance that would normally be safe can become lethal when contaminated with fentanyl. Assume contamination is possible.</p>
        </div>
    </section>

    <!-- Health Monitoring -->
    <section class="safe-consumption-section">
        <h2>Health Monitoring and Prevention</h2>
        
        <h3>Regular Health Checks:</h3>
        <ul>
            <li><strong>Blood-Borne Virus Testing:</strong> Get tested for HIV, Hepatitis B and C regularly. Vaccines available for some</li>
            <li><strong>Wound Monitoring:</strong> Check injection sites regularly for signs of infection</li>
            <li><strong>General Health:</strong> Regular medical checkups. Inform providers about substance use</li>
            <li><strong>Mental Health:</strong> Substance use often co-occurs with depression and anxiety. Seek mental health support</li>
            <li><strong>Dental Care:</strong> Substance use affects oral health. Regular dental care important</li>
        </ul>

        <h3>Infection Prevention:</h3>
        <ul>
            <li>Always use sterile, new equipment</li>
            <li>Vaccinate for Hepatitis A and B (if not immune)</li>
            <li>Clean injection sites with alcohol pads before injection</li>
            <li>Use clean water for preparation</li>
            <li>Practice good personal hygiene</li>
            <li>Seek immediate medical care for any infection signs</li>
        </ul>

        <h3>Overdose Prevention:</h3>
        <ul>
            <li>Test substances with fentanyl and reagent test strips</li>
            <li>Never use alone</li>
            <li>Always carry naloxone</li>
            <li>Know signs of overdose in yourself and others</li>
            <li>Start low with new batches or sources</li>
            <li>Avoid mixing substances</li>
        </ul>
    </section>

    <!-- Treatment Options -->
    <section class="safe-consumption-section">
        <h2>Path to Recovery</h2>
        
        <div class="info-box success">
            <strong>✓ Recovery Is Possible</strong>
            <p>Many people recover from substance use disorders. Effective treatments exist. Help is available. You deserve support and care.</p>
        </div>

        <h3>Treatment Options:</h3>
        <ul>
            <li><strong>Medication-Assisted Treatment (MAT):</strong> Methadone, buprenorphine, or naltrexone combined with counseling. Very effective</li>
            <li><strong>Behavioral Therapy:</strong> Cognitive-behavioral therapy, motivational interviewing, family therapy</li>
            <li><strong>Residential Programs:</strong> Inpatient treatment for intensive support</li>
            <li><strong>Outpatient Programs:</strong> Daily or weekly treatment allowing you to stay at home</li>
            <li><strong>Peer Support:</strong> Narcotics Anonymous, SMART Recovery, mutual support groups</li>
            <li><strong>Integrated Care:</strong> Treatment for substance use AND mental health simultaneously</li>
        </ul>

        <h3>Getting Help:</h3>
        <ul>
            <li>Call SAMHSA National Helpline: 1-800-662-4357 (free, confidential, 24/7)</li>
            <li>Text "HELLO" to 741741 (Crisis Text Line)</li>
            <li>Call 988 for mental health crisis support</li>
            <li>Visit FindTreatment.gov for local resources</li>
            <li>Talk to your doctor about treatment options</li>
        </ul>
    </section>

    <!-- Resources -->
    <section class="safe-consumption-section">
        <h2>Additional Resources</h2>
        
        <h3>Harm Reduction Organizations:</h3>
        <ul>
            <li>Harm Reduction Coalition - harmreduction.org</li>
            <li>NASTAD - National Association of State and Territorial AIDS Directors</li>
            <li>Erowid - substance information database</li>
            <li>DrugsData.org - independent substance testing results</li>
        </ul>

        <h3>Emergency Support:</h3>
        <ul>
            <li><strong>911:</strong> Emergency services (overdose, medical emergency)</li>
            <li><strong>988:</strong> Suicide & Crisis Lifeline</li>
            <li><strong>1-800-662-4357:</strong> SAMHSA National Helpline (treatment)</li>
            <li><strong>741741:</strong> Crisis Text Line (text "HELLO")</li>
        </ul>

        <div class="info-box">
            <strong>💡 Remember</strong>
            <p>You deserve safety, health, and dignity. Harm reduction doesn't judge. These practices recognize your humanity and prioritize your wellbeing while you decide your path forward.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="safe-consumption-section disclaimer-section">
        <h2>⚠️ Important Disclaimer</h2>
        <p>This information is provided for harm reduction and educational purposes only. It does not constitute medical, legal, or professional advice.</p>
        <ul>
            <li>These practices reduce but do not eliminate risk</li>
            <li>The safest choice is abstinence</li>
            <li>Always consult healthcare providers for medical advice</li>
            <li>Substance possession may be illegal in your jurisdiction</li>
            <li>If struggling with substance use, treatment is available and works</li>
            <li>Recovery is possible. You deserve help and support</li>
        </ul>
        <p><strong>You matter. Help is available. Recovery is possible. You are not alone.</strong></p>
    </section>
</div>
@endsection
