@extends('layouts.auth')

@section('title', 'Naloxone (Narcan) Guide - Life-Saving Overdose Reversal')

@section('breadcrumb', 'Naloxone (Narcan)')

@section('content')
<style>
    .narcan-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .narcan-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .narcan-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .narcan-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .narcan-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .narcan-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .narcan-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .narcan-section p {
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

    .narcan-section ul,
    .narcan-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .narcan-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .steps-container {
        display: grid;
        gap: 16px;
        margin-bottom: 16px;
    }

    .step {
        display: flex;
        gap: 16px;
        padding: 16px;
        background-color: #f5f5f5;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
    }

    .step-number {
        background: linear-gradient(135deg, #1a7a99 0%, #2a9db8 100%);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
        font-size: 18px;
    }

    .step-content h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin-bottom: 8px;
        font-weight: 600;
        margin: 0 0 8px 0;
    }

    .step-content p {
        margin: 0;
        font-size: 14px;
        color: #666666;
    }

    .overdose-signs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .sign-card {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 16px;
    }

    .sign-card h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .sign-card p {
        margin: 0;
        font-size: 14px;
        color: #666666;
        line-height: 1.6;
    }

    .formats-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    .formats-table th {
        background-color: #0d5b7c;
        color: white;
        padding: 16px;
        text-align: left;
        font-weight: 600;
    }

    .formats-table td {
        padding: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .formats-table tr:hover {
        background-color: #f5f5f5;
    }

    .timeline-box {
        background-color: #f0f9fb;
        border-left: 4px solid #2a9db8;
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .timeline-item {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .timeline-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .timeline-item strong {
        color: #0d5b7c;
        font-size: 15px;
    }

    .timeline-item p {
        margin: 6px 0 0 0;
        font-size: 14px;
        color: #666666;
    }

    .disclaimer-section {
        border: 2px solid #dc2626;
        background-color: #fee2e2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .narcan-header h1 {
            font-size: 24px;
        }

        .narcan-header p {
            font-size: 14px;
        }

        .narcan-section {
            padding: 16px;
        }

        .narcan-section h2 {
            font-size: 20px;
        }

        .step {
            flex-direction: column;
            gap: 12px;
        }

        .overdose-signs-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .narcan-header h1 {
            font-size: 20px;
        }

        .narcan-section {
            padding: 12px;
        }

        .narcan-section h2 {
            font-size: 18px;
        }

        .narcan-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }
    }
</style>

<div class="narcan-page">
    <!-- Header -->
    <div class="narcan-header">
        <h1>💉 Naloxone (Narcan) - Emergency Opioid Reversal</h1>
        <p>Life-saving medication that rapidly reverses opioid overdose. Learn how to recognize, respond, and administer.</p>
    </div>

    <!-- What Is Naloxone? -->
    <section class="narcan-section">
        <h2>What Is Naloxone (Narcan)?</h2>
        <p>Naloxone is a fast-acting medication that rapidly reverses opioid overdose. It works by blocking opioid receptors in the brain, displacing opioids and restoring normal respiration. Naloxone has no potential for abuse, cannot harm a person who is not on opioids, and can be administered by anyone—no medical training required.</p>
        
        <div class="info-box success">
            <strong>✓ Life-Saving Medication</strong>
            <p>Naloxone can bring a person back from a fatal overdose in 2-3 minutes. Having it available and knowing how to use it can mean the difference between life and death.</p>
        </div>

        <h3>Key Facts</h3>
        <ul>
            <li><strong>Rapid Action:</strong> Works within 2-3 minutes of administration</li>
            <li><strong>No Abuse Potential:</strong> Cannot be abused or cause euphoria</li>
            <li><strong>Safe If Misused:</strong> Has no effect on people without opioids in their system</li>
            <li><strong>Available Formats:</strong> Nasal spray (easiest) or injection</li>
            <li><strong>Affordable:</strong> Often free or very low cost</li>
            <li><strong>Legal:</strong> Available without prescription in many areas</li>
            <li><strong>Long Shelf Life:</strong> Remains effective for years if stored properly</li>
        </ul>
    </section>

    <!-- Recognizing Overdose -->
    <section class="narcan-section">
        <h2>Recognizing an Opioid Overdose</h2>
        <p>Acting quickly is critical. These signs indicate opioid overdose emergency:</p>
        
        <div class="overdose-signs-grid">
            <div class="sign-card">
                <h4>Breathing Changes</h4>
                <p>Slow or shallow breathing, difficulty breathing, or no breathing at all. May sound like gasping or choking.</p>
            </div>
            <div class="sign-card">
                <h4>Body Response</h4>
                <p>Unresponsive or unconscious. Cannot be awakened by voice or physical stimulation (shoulder shake).</p>
            </div>
            <div class="sign-card">
                <h4>Skin Color</h4>
                <p>Blue lips or fingernails. Pale or grayish skin color indicating poor oxygen circulation.</p>
            </div>
            <div class="sign-card">
                <h4>Physical Signs</h4>
                <p>Limp body, small pinpoint pupils (though this can vary). Cold, clammy skin.</p>
            </div>
            <div class="sign-card">
                <h4>Sounds</h4>
                <p>Gurgling or choking sounds. Snoring-type sounds. Silence may indicate loss of breathing.</p>
            </div>
            <div class="sign-card">
                <h4>When in Doubt</h4>
                <p>If uncertain—treat it as overdose. It is better to give Narcan unnecessarily than to hesitate and lose a life.</p>
            </div>
        </div>

        <div class="info-box danger">
            <strong>🚨 IMMEDIATE ACTION REQUIRED</strong>
            <p>If you suspect overdose: CALL 911 FIRST. Then administer naloxone while waiting for help. Do not be afraid of legal consequences—Good Samaritan laws protect you.</p>
        </div>
    </section>

    <!-- Emergency Response Steps -->
    <section class="narcan-section">
        <h2>How to Respond to an Overdose</h2>
        
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>Call 911 Immediately</h4>
                    <p>Call emergency services first. Tell them you suspect opioid overdose and your location. Stay on the line with dispatcher. Let them know you have Narcan and will administer it.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Try to Wake Them</h4>
                    <p>Shake their shoulders firmly. Call their name loudly. If no response, proceed with Narcan administration.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Administer Narcan</h4>
                    <p>Use nasal spray: Place person on their back, tilt head back, insert nozzle into nostril, and push plunger firmly. Or inject: Use auto-injector on outer thigh (through clothing is fine) and hold for 3 seconds.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h4>Monitor and Wait</h4>
                    <p>Stay with the person. Effects appear in 2-3 minutes. Person may wake up suddenly and be confused or agitated. This is normal. Stay calm and reassuring.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <h4>Second Dose if Needed</h4>
                    <p>If no improvement after 2-3 minutes, give second dose of Narcan (if available). Some overdoses require multiple doses. Keep administering if needed until emergency services arrive.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">6</div>
                <div class="step-content">
                    <h4>Wait for Emergency Services</h4>
                    <p>Do not leave the person alone. Place on their side (recovery position) if unconscious to prevent choking. Keep monitoring breathing. Provide information to paramedics upon arrival.</p>
                </div>
            </div>
        </div>

        <div class="info-box warning">
            <strong>⚠️ After Naloxone Administration</strong>
            <p>Person will likely feel acute withdrawal symptoms: body aches, sweating, anxiety, agitation. This is not dangerous but can be uncomfortable. Reassure them. Do not prevent their transport to hospital for evaluation.</p>
        </div>
    </section>

    <!-- Narcan Formats -->
    <section class="narcan-section">
        <h2>Narcan Formats and How to Use</h2>
        
        <table class="formats-table">
            <thead>
                <tr>
                    <th>Format</th>
                    <th>How to Use</th>
                    <th>Advantages</th>
                    <th>Considerations</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Nasal Spray (Narcan)</strong></td>
                    <td>Place nozzle in nostril (tilt head back), push plunger firmly with thumb</td>
                    <td>Easiest to use, no needle, faster training, works through clothing</td>
                    <td>Must ensure person is on back; may need both nostrils if severe</td>
                </tr>
                <tr>
                    <td><strong>Auto-Injector (Evzio)</strong></td>
                    <td>Remove from carrier tube, swing and push firmly onto outer thigh at 90°, hold 3 seconds</td>
                    <td>Clear voice instructions, auto-release needle safety mechanism</td>
                    <td>More expensive, requires thigh exposure, musculoskeletal barrier possible</td>
                </tr>
                <tr>
                    <td><strong>Injectable Solution</strong></td>
                    <td>Draw into syringe, inject into muscle (deltoid, gluteus, or thigh)</td>
                    <td>Inexpensive, long shelf life when stored properly</td>
                    <td>Requires needle and syringe, more training needed, needle safety concerns</td>
                </tr>
            </tbody>
        </table>

        <div class="info-box success">
            <strong>✓ Nasal Spray Recommended</strong>
            <p>For most people and situations, nasal spray is recommended. It's easiest to administer correctly, requires minimal training, and works effectively.</p>
        </div>
    </section>

    <!-- Timeline to Recovery -->
    <section class="narcan-section">
        <h2>Timeline: What to Expect After Narcan</h2>
        
        <div class="timeline-box">
            <div class="timeline-item">
                <strong>0-2 Minutes:</strong>
                <p>Naloxone begins working. Person may start to respond, cough, or move.</p>
            </div>
            <div class="timeline-item">
                <strong>2-3 Minutes:</strong>
                <p>Full effect. Person regains consciousness. Breathing improves. May be confused or disoriented.</p>
            </div>
            <div class="timeline-item">
                <strong>3-10 Minutes:</strong>
                <p>Person becomes alert. May experience acute withdrawal: sweating, body aches, anxiety, agitation. Reassure them.</p>
            </div>
            <div class="timeline-item">
                <strong>10-30 Minutes:</strong>
                <p>Person is conscious and breathing normally. Continue monitoring. Encourage hospital transport for evaluation.</p>
            </div>
            <div class="timeline-item">
                <strong>30-90 Minutes:</strong>
                <p>Naloxone effects last 30-90 minutes. Some opioids last longer. Hospital observation ensures safe monitoring as Narcan wears off.</p>
            </div>
        </div>

        <div class="info-box warning">
            <strong>⚠️ Important: Hospital Evaluation Required</strong>
            <p>Even after successful Narcan administration and recovery, hospital evaluation is essential. Some opioids are longer-acting than Narcan. Re-overdose can occur as Narcan wears off. Do not refuse medical transport.</p>
        </div>
    </section>

    <!-- Getting Naloxone -->
    <section class="narcan-section">
        <h2>How to Get Naloxone</h2>
        
        <h3>Free or Low-Cost Sources:</h3>
        <ul>
            <li><strong>Harm Reduction Programs:</strong> Many local programs distribute free Narcan</li>
            <li><strong>Syringe Services Programs:</strong> Often have free or low-cost naloxone</li>
            <li><strong>Substance Use Treatment Centers:</strong> Provide naloxone with training</li>
            <li><strong>Community Health Centers:</strong> Many distribute free kits</li>
            <li><strong>Public Health Departments:</strong> Often have naloxone programs</li>
            <li><strong>Pharmacies:</strong> Many states allow pharmacy dispensing without prescription</li>
        </ul>

        <h3>Online Resources:</h3>
        <ul>
            <li>SAMHSA National Helpline: 1-800-662-4357 (can direct to local programs)</li>
            <li>Harm Reduction Coalition: harmreduction.org</li>
            <li>FindTreatment.gov - locate recovery and harm reduction services</li>
            <li>Local public health department websites</li>
        </ul>

        <div class="info-box success">
            <strong>✓ Get Training</strong>
            <p>When getting Narcan, ask for brief training. Most programs provide quick instruction on nasal spray use. This takes only a few minutes and greatly increases confidence in an emergency.</p>
        </div>
    </section>

    <!-- Storage and Maintenance -->
    <section class="narcan-section">
        <h2>Storage and Maintenance of Narcan</h2>
        
        <h3>Storage Guidelines:</h3>
        <ul>
            <li>Store at room temperature (59-86°F / 15-30°C)</li>
            <li>Protect from extreme heat and cold</li>
            <li>Keep away from direct sunlight</li>
            <li>Store in a safe place, accessible in emergency</li>
            <li>Tell trusted people where Narcan is stored</li>
            <li>Check expiration date regularly</li>
            <li>Consider having multiple kits in different locations</li>
        </ul>

        <h3>Maintenance:</h3>
        <ul>
            <li>Check expiration date every month or quarterly</li>
            <li>Replace expired naloxone immediately</li>
            <li>If used, replace the kit promptly</li>
            <li>Inspect for visible damage or discoloration</li>
            <li>Keep spare kits at work, home, and with trusted friends</li>
            <li>Consider keeping one in your vehicle</li>
        </ul>
    </section>

    <!-- Good Samaritan Laws -->
    <section class="narcan-section">
        <h2>Good Samaritan Laws and Legal Protection</h2>
        
        <div class="info-box success">
            <strong>✓ Legal Protection Available</strong>
            <p>Many jurisdictions have Good Samaritan laws that protect you from criminal or civil liability when calling 911 for an overdose emergency or administering naloxone. These protections encourage life-saving action.</p>
        </div>

        <h3>What These Laws Protect:</h3>
        <ul>
            <li>Calling 911 for overdose emergency</li>
            <li>Administering naloxone in good faith</li>
            <li>Remaining at scene to administer aid</li>
            <li>Civil liability (in many states)</li>
            <li>Criminal prosecution related to overdose call (in many states)</li>
        </ul>

        <h3>Important Notes:</h3>
        <ul>
            <li>Laws vary by jurisdiction - check your local laws</li>
            <li>Even without specific laws, calling 911 is always the right action</li>
            <li>Emergency responders prioritize saving lives over enforcement</li>
            <li>Document that you called 911 and administered naloxone</li>
        </ul>
    </section>

    <!-- Resources -->
    <section class="narcan-section">
        <h2>Emergency Resources</h2>
        
        <h3>Emergency Numbers:</h3>
        <ul>
            <li><strong>911:</strong> Emergency services (overdose, medical emergency)</li>
            <li><strong>988:</strong> Suicide & Crisis Lifeline (mental health crisis)</li>
            <li><strong>1-800-662-4357:</strong> SAMHSA National Helpline (treatment and referral)</li>
        </ul>

        <h3>Support and Treatment:</h3>
        <ul>
            <li>Medication-Assisted Treatment (MAT): methadone, buprenorphine, naltrexone</li>
            <li>Counseling and behavioral therapy</li>
            <li>Peer support groups (NA, SMART Recovery, etc.)</li>
            <li>Inpatient and outpatient treatment programs</li>
        </ul>

        <div class="info-box">
            <strong>💡 Recovery Is Possible</strong>
            <p>If you or someone you know is struggling with opioid use, recovery is possible. Treatment works. Medication-assisted treatment combined with counseling has high success rates. Reach out for help today.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="narcan-section disclaimer-section">
        <h2>⚠️ Important Medical Disclaimer</h2>
        <p>This information is provided for educational and emergency response purposes only. It is not a substitute for professional medical advice, training, or instruction.</p>
        <ul>
            <li>Seek professional naloxone training through your local harm reduction program</li>
            <li>Always call 911 in case of overdose emergency</li>
            <li>This guide supplements but does not replace formal naloxone training</li>
            <li>Follow all instructions provided with your specific naloxone product</li>
            <li>Hospital evaluation is essential after overdose emergency</li>
            <li>Naloxone does not prevent re-overdose after it wears off</li>
        </ul>
        <p><strong>Recovery is possible. Help is available. You are not alone. Call 988 for support.</strong></p>
    </section>
</div>
@endsection
