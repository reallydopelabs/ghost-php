<?php

namespace ReallyDope\Ghost;

/**
 * @property string $id The unique identifier of the member.
 * @property string $uuid The UUID of the member.
 * @property string $email The email address of the member.
 * @property string $name The name of the member.
 * @property string|null $note A note about the member, if any.
 * @property mixed|null $geolocation The geolocation of the member (if available).
 * @property bool $subscribed Indicates if the member is subscribed.
 * @property string $created_at The creation timestamp of the member.
 * @property string $updated_at The last update timestamp of the member.
 * @property array $labels An array of labels associated with the member.
 * @property array $subscriptions An array of subscriptions related to the member.
 * @property string $avatar_image URL of the member's avatar image.
 * @property bool $comped Indicates if the member has complimentary access.
 * @property int $email_count The number of emails sent to the member.
 * @property int $email_opened_count The number of emails opened by the member.
 * @property float|null $email_open_rate The email open rate of the member.
 * @property string $status The status of the member (e.g., "paid").
 * @property string|null $last_seen_at The last seen timestamp of the member.
 * @property array $attribution An array of attribution data for the member.
 * @property array $tiers An array of tiers the member belongs to.
 * @property array $email_suppression An array indicating email suppression status.
 * @property array $newsletters An array of newsletters associated with the member.
 */
class Member extends Resource
{
    //
}
