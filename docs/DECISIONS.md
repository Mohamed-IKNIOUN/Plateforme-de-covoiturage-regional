# Journal des décisions et réponses aux questions à trancher pour la plateforme de covoiturage.

-->Can a seat freed at an intermediate stop be resold for the rest of the trip?
Yes — that's the whole point of segment-based booking.

A Trip has TripStops at positions 0..N. A seat is not reserved "for the trip", it's reserved per segment [i, i+1]. The same physical seat can carry:

Passenger A from pos 0 → 1

Passenger B from pos 1 → 2

As long as at no segment the sum of bookedSeats exceeds totalSeats.


