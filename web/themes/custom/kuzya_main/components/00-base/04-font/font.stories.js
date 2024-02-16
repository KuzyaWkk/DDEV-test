import font from './font.twig';

import fontData from './fonts.yml';

/**
 * Add storybook definition for Animations.
 */
export default { title: 'Base/Font' };

export const Families = () => font(fontData);
