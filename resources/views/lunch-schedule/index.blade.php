@extends('layouts.app')

@section('title', 'Küchendienst')

@section('content')
<!-- Notification Container -->
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="max-w-7xl mx-auto" x-data="lunchCalendar()" x-init="init()">
    <!-- Header with Info -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Küchendienst</h1>

        <!-- Urgent needs notification -->
        <div x-show="needsVolunteers.length > 0" class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
            <p class="text-amber-800 font-semibold mb-2">Hilfe benötigt!</p>
            <p class="text-sm text-amber-700 mb-3">
                Folgende Tage brauchen noch Hilfe beim Küchendienst:
            </p>
            <div class="space-y-1">
                <template x-for="shift in needsVolunteers" :key="shift.id">
                    <div class="flex items-center justify-between">
                        <span class="text-sm" x-text="shift.formatted_date"></span>
                        @auth
                            <button
                                @click="signupForShift(shift.id)"
                                :disabled="loading"
                                class="text-xs px-2 py-1 bg-steiner-blue text-white rounded hover:bg-steiner-dark disabled:opacity-50">
                                Anmelden
                            </button>
                        @endauth
                    </div>
                </template>
            </div>
            @guest
                <p class="text-xs text-amber-600 mt-3">
                    <a href="{{ route('login') }}" class="underline">Anmelden</a> um sich einzutragen.
                </p>
            @endguest
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                <strong>Küchendienst Information:</strong> Das Mittagessen wird von Eltern zubereitet.
                Der Küchendienst dauert etwa von 11:00 bis 13:30 Uhr und umfasst Vorbereitung, Kochen und Aufräumen.
            </p>
        </div>
    </div>

    <!-- Month Navigation -->
    <div class="flex items-center justify-between mb-6">
        <button @click="changeMonth(-1)"
                :disabled="loading || !previousMonth"
                x-show="previousMonth"
                class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50">
            ← <span x-text="getPreviousMonthName()"></span>
        </button>
        <div x-show="!previousMonth" class="w-32"></div>

        <h2 class="text-xl font-semibold text-gray-800" x-text="currentMonthFormatted"></h2>

        <button @click="changeMonth(1)" :disabled="loading"
                class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50">
            <span x-text="getNextMonthName()"></span> →
        </button>
    </div>

    <!-- Loading indicator -->
    <div x-show="loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-steiner-blue"></div>
    </div>

    <!-- Calendar Grid -->
    <div x-show="!loading" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Days of Week Header -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            @foreach(['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'] as $day)
                <div class="p-3 text-center text-sm font-medium text-gray-700 {{ in_array($day, ['Samstag', 'Sonntag']) ? 'bg-gray-100' : '' }}">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <template x-for="(week, weekIndex) in calendar" :key="weekIndex">
            <div class="grid grid-cols-7 border-b border-gray-200 last:border-b-0">
                <template x-for="(day, dayIndex) in week" :key="day.date">
                    <div :class="{
                            'bg-gray-50': !day.isCurrentMonth || day.isWeekend || day.isPast,
                            'bg-amber-50': day.isToday,
                            'bg-gray-100': day.isWeekend && day.isCurrentMonth,
                            'opacity-50': day.isPast && day.isCurrentMonth
                         }"
                         class="min-h-[100px] p-2 border-r border-gray-200 last:border-r-0">

                        <!-- Date Number -->
                        <div :class="{'text-gray-400': !day.isCurrentMonth, 'text-gray-700': day.isCurrentMonth}"
                             class="text-sm font-medium mb-1" x-text="day.day"></div>

                        <!-- Only show shifts for weekdays in current month -->
                        <template x-if="!day.isWeekend && day.isCurrentMonth && day.shift">
                            <div class="text-xs">
                                <!-- Filled shift -->
                                <template x-if="day.shift.is_filled">
                                    <div>
                                        <div class="px-2 py-1 rounded"
                                             :class="day.isPast ? 'bg-gray-100 text-gray-600' : 'bg-steiner-lighter text-steiner-dark'">
                                            <span x-text="day.shift.cook_name || 'Besetzt'"></span>
                                        </div>
                                        @auth
                                            <button x-show="day.shift.is_mine && day.shift.can_cancel && !day.isPast"
                                                    @click="cancelShift(day.shift.id)"
                                                    :disabled="loading"
                                                    class="mt-1 text-xs text-red-600 hover:text-red-800 disabled:opacity-50">
                                                Abmelden
                                            </button>
                                        @endauth
                                    </div>
                                </template>

                                <!-- Open shift -->
                                <template x-if="!day.shift.is_filled">
                                    <div>
                                        <div class="px-2 py-1 rounded text-center"
                                             :class="day.isPast ? 'bg-gray-100 text-gray-600' : 'bg-amber-100 text-amber-800'">
                                            <span x-text="day.isPast ? 'Nicht besetzt' : 'Offen'"></span>
                                        </div>
                                        @auth
                                            <button x-show="day.shift.can_signup && !day.isPast"
                                                    @click="signupForShift(day.shift.id)"
                                                    :disabled="loading"
                                                    class="mt-1 text-xs text-steiner-blue hover:text-steiner-dark disabled:opacity-50">
                                                Anmelden
                                            </button>
                                        @endauth
                                    </div>
                                </template>

                                <!-- Notes indicator -->
                                <div x-show="day.shift.notes" class="mt-1 text-xs text-gray-600" :title="day.shift.notes">
                                    📝
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <!-- Legend -->
    <div class="mt-6 flex flex-wrap gap-4 text-sm text-gray-600">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-steiner-lighter border border-steiner-blue rounded mr-2"></div>
            <span>Besetzt</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-amber-100 border border-amber-300 rounded mr-2"></div>
            <span>Noch offen</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-amber-50 border border-amber-300 rounded mr-2"></div>
            <span>Heute</span>
        </div>
    </div>

    @guest
        <div class="mt-8 text-center p-6 bg-gray-50 rounded-lg">
            <p class="text-gray-700 mb-4">
                Möchten Sie beim Küchendienst helfen?
            </p>
            <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-steiner-blue text-white rounded hover:bg-steiner-dark">
                Anmelden und mitmachen
            </a>
        </div>
    @endguest
</div>

<script>
function lunchCalendar() {
    return {
        calendar: [],
        needsVolunteers: [],
        currentMonth: '',
        currentMonthFormatted: '',
        previousMonth: null,
        nextMonth: '',
        canNavigatePrevious: true,
        loading: false,

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            let month = urlParams.get('month') || new Date().toISOString().slice(0, 7);

            // Prevent loading past months
            const currentMonth = new Date().toISOString().slice(0, 7);
            if (month < currentMonth) {
                month = currentMonth;
            }

            this.loadMonth(month);
        },

        async loadMonth(month) {
            this.loading = true;
            try {
                const response = await fetch(`/api/lunch-schedule?month=${month}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) throw new Error('Failed to load calendar');

                const data = await response.json();
                this.calendar = data.calendar;
                this.needsVolunteers = data.needsVolunteers;
                this.currentMonth = data.currentMonth;
                this.currentMonthFormatted = data.currentMonthFormatted;
                this.previousMonth = data.previousMonth;
                this.nextMonth = data.nextMonth;
                this.canNavigatePrevious = data.canNavigatePrevious;

                // Update URL without page reload
                const url = new URL(window.location);
                url.searchParams.set('month', month);
                window.history.pushState({}, '', url);
            } catch (error) {
                console.error('Error loading calendar:', error);
                this.showNotification('Fehler beim Laden des Kalenders', 'error');
            } finally {
                this.loading = false;
            }
        },

        changeMonth(direction) {
            const targetMonth = direction === -1 ? this.previousMonth : this.nextMonth;
            if (targetMonth) {
                this.loadMonth(targetMonth);
            }
        },

        getPreviousMonthName() {
            if (!this.previousMonth) return '';
            const date = new Date(this.previousMonth + '-01');
            return date.toLocaleDateString('de-DE', { month: 'long' });
        },

        getNextMonthName() {
            if (!this.nextMonth) return '';
            const date = new Date(this.nextMonth + '-01');
            return date.toLocaleDateString('de-DE', { month: 'long' });
        },

        async signupForShift(shiftId) {
            this.loading = true;
            try {
                const response = await fetch(`/api/lunch-schedule/${shiftId}/signup`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Reload current month to refresh data
                    await this.loadMonth(this.currentMonth);

                    // Show success message
                    if (data.message) {
                        this.showNotification(data.message, 'success');
                    }
                } else {
                    this.showNotification(data.error || 'Fehler beim Anmelden', 'error');
                }
            } catch (error) {
                console.error('Error signing up:', error);
                this.showNotification('Fehler beim Anmelden', 'error');
            } finally {
                this.loading = false;
            }
        },

        async cancelShift(shiftId) {
            if (!confirm('Möchten Sie sich vom Küchendienst abmelden?')) {
                return;
            }

            this.loading = true;
            try {
                const response = await fetch(`/api/lunch-schedule/${shiftId}/cancel`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Reload current month to refresh data
                    await this.loadMonth(this.currentMonth);

                    // Show success message
                    if (data.message) {
                        this.showNotification(data.message, 'success');
                    }
                } else {
                    this.showNotification(data.error || 'Fehler beim Abmelden', 'error');
                }
            } catch (error) {
                console.error('Error canceling:', error);
                this.showNotification('Fehler beim Abmelden', 'error');
            } finally {
                this.loading = false;
            }
        },

        showNotification(message, type = 'info') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');

            // Set classes based on type
            let bgColor = 'bg-steiner-blue';
            if (type === 'success') bgColor = 'bg-steiner-blue';
            if (type === 'error') bgColor = 'bg-red-500';

            notification.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            container.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    }
}
</script>
@endsection