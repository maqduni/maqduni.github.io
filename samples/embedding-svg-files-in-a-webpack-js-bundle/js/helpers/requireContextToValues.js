export default function requireContextToValues(context) {
    return context.keys().map((key) => context(key).default);
}
